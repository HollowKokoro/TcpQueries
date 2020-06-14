<?php
//declare(strict_types = 1);

$config = require_once "./config.php";
require_once "./ServerClient.php";

class Server
{
    private $socket;
    private $clients;

    public function __construct(string $address, int $port)
    {
        $this->clients = [];
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n" . socket_strerror(socket_last_error()) . "\n");
        socket_bind($this->socket, $address, $port) or die("Could not bind to socket\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        socket_listen($this->socket, 3) or die("Could not set up socket listener\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        socket_set_nonblock($this->socket);
    }

    public function handle(): void
    {
        while (true) {
            $newClient = $this->getNewClient();
            if ($newClient !== null) {
                $this->clients[] = $newClient;
            }

            $data = $this->tryToReadFromClient($newClient);
            if ($data !== null) {
                
                foreach ($this->clients as $newClient) {
                    socket_write($newClient, $data, strlen($data));
                }
            }
            
            usleep(50000);
        }
    }

    private function getNewClient(): ?ServerClient
    {
        $client = socket_accept($this->socket);
        if ($client === false) {
            return null;
        }
        
        return new ServerClient($client);
    }

    private function tryToReadFromClient($newClient): ?string
    {
        $readMessage = "";
        while ($readPart = socket_read($newClient, 1024, PHP_NORMAL_READ)) {
            if ($readMessage === false) {
                $readMessage = null;
                break;
            }

            if ($readPart == "") {
                break;
            }
            $readMessage .= $readPart;
        }
        return $readMessage;
    }
}

$server = new Server($config["address"], $config["port"]);
$server->handle();