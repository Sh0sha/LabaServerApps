<?php
namespace app\DTO;

class DatabaseInfoDTO
{
    public string $connection; // для названия типа базы данных и названия бд
    public string $database;
  
    public function __construct(string $connection, string $database)            // Конструктор класса который принимает значения для conn и db
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
