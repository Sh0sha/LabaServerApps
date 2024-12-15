<?php

namespace App\DTO\ChangeLogDTO;

class ChangeLogCollectionDTO
{
  private array $logs;

  public function __construct(array $logs) //  принимает аргументы и настраивает свойства объекта при его создании
  {
    $this->logs = $logs;
  }

  public function toArray(): array
  {
    return array_map(fn(ChangeLogDTO $log) => $log->toArray(), $this->logs);
  }
}
