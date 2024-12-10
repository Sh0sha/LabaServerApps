<?php
namespace App\Http\Requests;
use App\DTO\LoginDTO;
use Illuminate\Foundation\Http\FormRequest;

class LoginReq extends FormRequest
{
    // Определяем правила валидации
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'regex:/^[A-Z][a-zA-Z]{6,}$/'],
            'password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[a-z]/', 'regex:/\d/', 'regex:/\W/'],
        ];
    }

    // Возвращаем DTO для авторизации
    public function toDTO(): LoginDTO
    {
        return new LoginDTO($this->username, $this->password);
    }
}