<?php 
declare(strict_types = 1);

abstract class Server
{
    /**
     * @var $socket Открытый сокет
     */
    private $socket;

    /**
     * @var array $clients Каждый элемент массива содержит 
     * отправленные данные соответствующего клиента
     */
    private array $clients;

    /**
     * Конструктор, 
     *
     * @param string $address адрес пользователя
     * @param integer $port порт пользователя
     */
    abstract public function __construct(string $address, int $port);

    /**
     * 
     *
     * @return void
     */
    abstract public function handle(): void;

    /**
     * Извлекает первый запрос соединения из очереди ожидающих соединений
     * для прослушивающего сокета, создает новый подключенный сокет и
     * возвращает новый дескриптор файла, ссылающийся на этот сокет.
     * В этот момент устанавливается соединение между клиентом и сервером,
     * и они готовы к передаче данных.
     *
     * @return ServerClient|null
     */
    abstract protected function getNewClient(): ?ServerClient;
}