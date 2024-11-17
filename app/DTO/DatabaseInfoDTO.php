<?php
namespace app\DTO;

class DatabaseInfoDTO
{
    public string $connection;
    public string $database;

    public function __construct(string $connection, string $database)
    {
        $this->connection = $connection;
        $this->database = $database;
    }

    public function toArray(): array             // преобразования данных DTO в массив
    {
        return [
            'Подключение' => $this->connection,
            'Название базы данных' => $this->database,
        ];
    }
}
