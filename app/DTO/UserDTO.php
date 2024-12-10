<?php

namespace App\DTO;

use App\Models\User;

class UserDTO
{
    public string $id;
    public string $username;
    public string $email;
    public string $birthday;

    /**
     * Конструктор UserDTO.
     * 
     * @param string $id ID пользователя
     * @param string $username Имя пользователя
     * @param string $email Электронная почта пользователя
     * @param string $birthday Дата рождения пользователя
     */
    public function __construct(string $id, string $username, string $email, string $birthday)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->birthday = $birthday;
    }

    /**
     * Фабричный метод для создания DTO из модели User
     * 
     * @param User $user Модель пользователя
     * @return UserDTO
     */
    public static function fromModel(User $user): self
    {
        return new self(
            $user->id,
            $user->username,
            $user->email,
            $user->birthday
        );
    }

    /**
     * Преобразование DTO в массив.
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'birthday' => $this->birthday,
        ];
    }
}
