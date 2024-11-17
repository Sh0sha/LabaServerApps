<?php
namespace App\Http\Controllers;

use App\DTO\ServerInfoDTO;
use App\DTO\ClientInfoDTO;
use App\DTO\DatabaseInfoDTO;
use Illuminate\Http\Request;        // подключаем класс Request для работы с http-запросами

class InfoController extends Controller
{
    public function serverInfo()
    {
        // Создаём объект DTO для данных о сервере (передавая в него нужныед анные)
        $dto = new ServerInfoDTO(phpversion());
        // Преобразуем DTO в массив и возвращаем в формате JSON
        return response()->json($dto->toArray());       // Метод toArray() преобразует объект DTO в массив для передачи клиенту
    }

    public function clientInfo(Request $request)
    {
        // Создаём объект DTO для данных о клиенте
        $dto = new ClientInfoDTO($request->ip(), $request->userAgent());

        // Преобразуем DTO в массив и возвращаем в формате JSON
        return response()->json($dto->toArray());
    }

    public function databaseInfo()
    {
        // Создаём объект DTO для данных о базе данных
        $dto = new DatabaseInfoDTO(
            config('database.default'),
            config('database.connections.mysql.database')
        );

        // Преобразуем DTO в массив и возвращаем в формате JSON
        return response()->json($dto->toArray());
    }
}
