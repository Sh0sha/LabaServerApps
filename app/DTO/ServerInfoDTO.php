<?php
namespace app\DTO;

class ServerInfoDTO
{
    public string $phpVersion;
  

    public function __construct(string $phpVersion)          // Конструктор класса который принимает значения версии пхп
    {
        $this->phpVersion = $phpVersion;
      
    }

    public function toArray(): array        // преобразования данных DTO в массив.
    {
        return [
            'Версия PHP' => $this->phpVersion,
        ];
    }
}
