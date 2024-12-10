<?php

namespace App\Http\Requests;

use App\DTO\RegisterDTO; 
use Illuminate\Foundation\Http\FormRequest; 

class RegisterReq extends FormRequest
{
    /**
     * Определяем, авторизован ли пользователь для выполнения этого запроса.
     */
    public function authorize(): bool
    {
        
        return true; // Разрешаем выполнение запроса
    }
    
    /**
     * Правила валидации для запроса регистрации.
     */
    public function rules(): array
    {
        return [
            // начинается с заглавной буквы, минимум 7 символов, уникально
            'username' => ['required', 'string', 'regex:/^[A-Z][a-zA-Z]{6,}$/', 'unique:users,username'],
            //корректный формат и уникальный
            'email' => ['required', 'email', 'unique:users,email'],
            //  минимум 8 символов, содержит заглавную букву, строчную букву, цифру и спецсимвол
            'password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/\d/', 'regex:/\W/'],
            // должно совпадать с полем 'password'
            'c_password' => ['required', 'same:password'],
            // формат YYYY-MM-DD
            'birthday' => ['required', 'date_format:Y-m-d'],
        ];
    }

    /**
     * Преобразует данные из запроса в объект DTO для регистрации.
     */
    public function toDTO(): RegisterDTO
    {
        // Создаем новый экземпляр DTO с данными из запроса
        return new RegisterDTO(
            $this->username, 
            $this->email,     
            $this->password,  
            $this->birthday   
        );
    }
}
