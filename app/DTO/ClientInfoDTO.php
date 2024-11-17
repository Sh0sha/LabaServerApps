<?php
namespace app\DTO;

class ClientInfoDTO  

{
    public string $ip;          //  хранения ip клиента
    public string $userAgent; // информации о юзер агенте клиента (браузера)

    public function __construct(string $ip, string $userAgent)      // Конструктор класса который принимает значения для IP-адреса и User-Agent
    {
        $this->ip = $ip;        
        $this->userAgent = $userAgent;
    }

    public function toArray(): array        // преобразования данных DTO в массив
    {
        return [
            'ip' => $this->ip,          // Возвращаем IP-адрес клиента и юзер агента
            'user_agent' => $this->userAgent,
        ];
    }
}
