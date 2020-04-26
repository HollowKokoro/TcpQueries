<?php

$config = require_once "./config.php";
require_once "./serverClient.php";

class Server
{
    private $socket;
    private $clients;

    public function __construct($address, $port)
    {
        $this->clients = [];
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n" . socket_strerror(socket_last_error()) . "\n");
        socket_bind($this->socket, $address, $port) or die("Could not bind to socket\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        socket_listen($this->socket, 3) or die("Could not set up socket listener\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        socket_set_nonblock($this->socket);
    }

    public function handle()
    {
        while (true) {
            if ($newClient = socket_accept($this->socket) && is_resource(socket_accept($this->socket))) {
                $this->clients[] = new ServerClient();
                if ($read = socket_read($newClient, 1024)) {
                    echo $read;
                    if (is_string($read)) {
                        socket_write($newClient, $read, strlen($read));
                    }
                }
            }
        }  
    }
}

$connectWithClient = new Server($config["address"], $config["port"]);
$connectWithClient->handle();