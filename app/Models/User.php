<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; //  использования фабрик при тестировании
use Illuminate\Foundation\Auth\User as Authenticatable; //  для аутентифицируемых моделей
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens; 

    /**
     * Массив атрибутов, которые разрешены для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = [ // Позволяет массово задавать значения для указанных полей
        'username',
        'email',   
        'password', 
        'birthday', 
    ];

    /**
     * Атрибуты, которые скрыты при сериализации.
     *
     * @var array<int, string>
     */
    protected $hidden = [ // Поля, которые не будут отображаться в JSON-ответах
        'password',        // Скрытие пароля
        'remember_token',  
    ];

    /**
     * Приведение типов атрибутов.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
