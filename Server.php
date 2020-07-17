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
        $newClient = null;
        while (true) {
            $newClient = $this->getNewClient();
            if ($newClient !== null) {
                $this->clients[] = $newClient;
            }
            usleep(50000);
            foreach ($this->clients as $client) {
                (new ServerClient($newClient))->tryToReadFromClient();
            }
        }
    }

    /**
     * Передаёт сокет в ServerClient и возвращает сегменты переданные
     * клиентом на сервер либо null.
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