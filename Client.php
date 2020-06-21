<?php 
declare(strict_types = 1);

abstract class Client
{
    /**
     * @var $socket Открытый сокет
     */
    private $socket;
    
    /**
     * Конструктор, открывает клиентский сокет и пытается подсоединится к сокету сервера.
     *
     * @param string $address адрес пользователя
     * @param integer $port порт пользователя
     */
    abstract public function __construct(string $address, int $port);

    /**
     * Пытается прочитать пользовательский ввод и прочитать
     * данные отправленные с сервера каждые 50000 миллисекунд.
     *
     * @return void
     */
    abstract public function handle(): void;
    
    /**
     * Пытается прочитать данные с сервера. Возвращает null если данных нет.
     *
     * @throws RuntimeException
     * @return string|null
     */
    abstract protected function tryToReadFromServer(): ?string;
    
    /**
     * Пытается прочитать введенные пользовательские данные. Возвращает null если их нет.
     * 
     * @throws RuntimeException
     * @return string|null
     */
    abstract protected function tryToReadFromKeyboard(): ?string;
}