<?php 
declare(strict_types = 1);

class Server
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
     * Конструктор, создаёт сокет, привязывает адрес к сокету по номеру порта
     * и ждёт соединения клиента в неблокирующем режиме
     *
     * @param string $address адрес пользователя
     * @param integer $port порт пользователя
     */
    public function __construct(string $address, int $port)
    {
        $this->clients = [];
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n" . socket_strerror(socket_last_error()) . "\n");
        socket_bind($this->socket, $address, $port) or die("Could not bind to socket\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        socket_listen($this->socket, 3) or die("Could not set up socket listener\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        socket_set_nonblock($this->socket);
    }
    
    /**
     * 
     *
     * @return void
     */
    public function handle(): void
    {
        while (true) {
            # code...
        }
        foreach ($this->clients as ) {
            # code...
        }
    }

    /**
     * Извлекает первый запрос соединения из очереди ожидающих соединений
     * для прослушивающего сокета, создает новый подключенный сокет и
     * возвращает новый дескриптор файла, ссылающийся на этот сокет.
     * В этот момент устанавливается соединение между клиентом и сервером,
     * и они готовы к передаче данных.
     *
     * @return ServerClient|null
     */
    private function getNewClient(): ?ServerClient
    {
        $client = socket_accept($this->socket);
        if ($client === false) {
            return null;
        }
        
        return new ServerClient($client);
    }
}