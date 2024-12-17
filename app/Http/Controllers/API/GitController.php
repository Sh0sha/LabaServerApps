<?php

namespace App\Http\Controllers\API;
// gредназначен для обновления проекта на сервере при получении уведомлений от Git
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class GitController extends Controller
{
    //
    // Проверить секретный ключ, переданный в запросе
    // Убедиться, что одновременно выполняется только один процесс обновления
    // Выполнить команды Git (reset, checkout, pull) для обновления проекта
    //Логировать важные данные (дата, IP-адрес, результаты команд)
    // 
    public function handleGitWebhook(Request $request)  
    {
        $gitBinary = '"C:/Program Files/Git/bin/git.exe"';
        $repositoryPath = 'C:\Users\Professional\lb6';

        $secretKey = env('GIT_SECRET_KEY'); // Получаем ключ из .env
        $inputSecretKey = $request->input('secret_key'); // Получаем ключ из запроса

        // Проверка наличия ключа в .env
        if (empty($secretKey)) {
            return response()->json(['message' => 'Server secret key is not configured'], 500);
        }

        // Проверка наличия ключа в запросе
        if (empty($inputSecretKey)) {
            return response()->json(['message' => 'Request secret key is poteryan'], 400);
        }

        // Сравнение ключей
        if ($inputSecretKey !== $secretKey) {
            return response()->json(['message' => 'Invalid secret key'], 403);
        }

        $lock = Cache::lock('git-update-lock', 30); // cache lock - исключения одновременного выполнения двух обновлений

        if (!$lock->get()) {
            return response()->json(['message' => 'Please try again later'], 429);
        }

        try {
            // Логирование даты и IP-адреса
            $ipAddress = $request->ip();
            $currentDate = now()->toDateTimeString();
            Log::info("Git hook work", [
                'date' => $currentDate,
                'ip_address' => $ipAddress,
            ]);

            // Выполнение Git-операций
            $projectPath = base_path(); // Путь к проекту
            $resetChanges = $this->executeCommand("reset --hard", $projectPath); //выполняет жесткое сбрасывание локальных изменений в Git
            $branchSwitch = $this->executeCommand("checkout master", $projectPath);
            $pullChanges = $this->executeCommand("pull origin master", $projectPath); 
//выполнение команды pull для получения последних изменений из удаленного репозитория

            // Логирование выполнения
            Log::info("Git operation complete", [
                'branch_switch' => $branchSwitch,
                'reset_changes' => $resetChanges,
                'pull_changes' => $pullChanges,
            ]);

            return response()->json([
                'message' => 'Project success Update on GIT.',
                'logs' => [
                    'branch_switch' => $branchSwitch,
                    'reset_changes' => $resetChanges,
                    'pull_changes' => $pullChanges,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error during Git operations", ['error' => $e->getMessage()]);
            return response()->json(['message' => ' error'], 500);
        } finally {
            // Освобождение блокировки
            $lock->release();
        }// блоке освобождается кэшированная блокировка, чтобы другие запросы могли запускать обновление после завершения текущего
    }



    private function executeCommand(string $command, string $workingDirectory): string
    {
        $gitPath = '"C:\\Program Files\\Git\\cmd\\git.exe"'; // Путь к git.exe

        // Формируем полную команду
        $fullCommand = $gitPath . ' ' . $command;  //  Объединяет путь к Git с командой

        // Переключаемся в рабочую директорию и выполняем команду
        chdir($workingDirectory);
        // Исполняем команду
        exec($fullCommand . " 2>&1", $output, $statusCode);
        // 2>&1 перенаправляет стандартный вывод ошибок в стандартный вывод, чтобы ошибки и результат оказались в массиве $output
        // $statusCode: код возврата команды (0 — успех, другое значение — ошибка).

        // Проверяем статус выполнения
        if ($statusCode !== 0) {
            throw new \Exception(join(" ", $output));
        }

        return join(" ", $output); // Возвращаем результат выполнения
    }
}
