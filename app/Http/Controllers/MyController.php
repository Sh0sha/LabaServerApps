<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginReq;           // Запрос для валидации данных при авторизации
use App\Http\Requests\RegisterReq;      // Запрос для валидации данных при регистрации
use App\DTO\LoginDTO;          
use App\DTO\UserDTO; 
use App\DTO\RegisterDTO; 
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request; 
use Illuminate\View\View; 
use Illuminate\Support\Facades\Hash; // Для хеширования паролей

class MyController extends Controller
{
      //  регистрации нового юзера
    public function register(RegisterReq $request)
    {
        
    // Получаем DTO с данными из запроса
    $dto = $request->toDTO();
  
    // создаем нового юзера на основе DTO
    $user = User::create([
        'username' => $dto->username,
        'email' => $dto->email,
        'password' => Hash::make($request->password), // хешим пароль
        'birthday' => $dto->birthday,
    ]);
    // // Генерируем токен для нового юзера
    // $token = $user->createToken('authToken')->plainTextToken;

    // Возвращаем данные юзера и токен
    return response()->json([
        'message' => 'Регистрация прошла!',
        'user' => [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ],
    ], 201);

    }



    //  авторизации юзера
    public function login(LoginReq $request)
    {
        $dto = $request->toDTO(); // Преобразуем запрос в DTO

        // Проверяем правильность логина и пароля
        if (!Auth::attempt(['username' => $dto->username, 'password' => $dto->password])) 
        {
            return response()->json(['message' => 'Error 401, oshibka auth'], 401); // Ошибка, если данные неверные
        }

        $user = Auth::user(); // Получаем текущего авторизованного юзера

        // Проверяем лимит активных токенов
        $maxTokens = (int) config('auth.max_tokens', env('MAX_ACTIVE_TOKENS', 5)); // Максимальное число токенов из конфигурации
        if ($user->tokens()->count() >= $maxTokens) {
            return response()->json(['message' => 'lIMIT tokens!!'], 403); // Ошибка, если лимит превышен
        }

        $user->tokens()->delete(); // Удаляем предыдущие токены

        // Генерируем новый токен
        $token = $user->createToken('authToken')->plainTextToken; // оригинальный токен без хеширвоания

        return response()->json(['token' => $token], 200); // Возвращаем токен
    }





    // sполучения данных  авторизованного юзера
    public function me(Request $request)
    {
        $user = $request->user(); // Получаем текущего пользователя

        if (!$user) {
            return response()->json(['message' => 'user  not found'], 404);
        }
    
        // Проверяем, есть ли у пользователя токены
        if ($user->tokens()->count() === 0) {
            return response()->json(['message' => 'No auth!'], 403);
        }
    
        // Преобразуем пользователя в DTO и возвращаем данные
        $userDTO = UserDTO::fromModel($user);
        return response()->json($userDTO->toArray(), 200);
    }






    //разлогирования (удаления текущего токена)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // Удаляем текущий токен

        return response()->json(['message' => 'Вышел'], 200); // Сообщение об успешном выходе
    }





    //  получения списка активных токенов текущего юзера
    public function tokens(Request $request)
    {
        $user = $request->user(); // Получаем текущего пользователя

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Проверяем, есть ли токены
        if ($user->tokens()->count() === 0) {
            return response()->json(['message' => 'User no auth'], 403);
        }
    
        // Формируем список токенов
        $tokens = $user->tokens->map(function ($token) {
            return [
                'tokenable_id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
            ];
        });
    
        return response()->json($tokens, 200);
    }





    //  разлогирования (удаления) всех токенов текущего юзера
    public function logoutAll(Request $request)
    {   
        $user = $request->user();// Получаем текущего пользователя

        // $request->user()->tokens()->delete(); // Удаляем все токены юзера
       
        foreach ($user->tokens as $token) { $token->delete();}

       
        return response()->json(['message' => 'All tokens are deleted!!! '], 200); // Сообщение об успешном отзыве всех токенов
    }




    //  смены пароля юзера
    public function changePassword(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'username' => ['required', 'exists:users,username'], // Проверяем, что пользователь существует
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/\d/', 'regex:/\W/'], // Новый пароль
            'confirm_new_password' => ['required', 'same:new_password'], // Подтверждение нового пароля
        ]);
    
        // Находим пользователя по имени
        $user = User::where('username', $request->username)->first();
    
        if (!$user) {
            return response()->json(['message' => 'User not fountd'], 404);
        }
    
        // Проверяем, что текущий пароль введен правильно
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is wrong'], 403);
        }
    
        // Обновляем пароль пользователя
        $user->update([
            'password' => bcrypt($request->new_password),
        ]);
    
        // Отзываем все токены пользователя
        $user->tokens()->delete();
    
        return response()->json(['message' => "Password '{$user->username}' changed!"], 200);
    }
    
    
}
