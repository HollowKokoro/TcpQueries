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
            $socket = $this->socketForClients();
            if ($socket === false) {
                null;
            }
            $this->clients => new ServerClient();
            usleep(50000);
        }
    }

    private function socketForClients()
    {
        return socket_accept($this->socket);

    }
}

$server = new Server($config["address"], $config["port"]);
$server->handle();
