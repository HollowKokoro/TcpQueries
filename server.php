<?php

$config = require_once "./config.php";
require_once "./clientToServer.php";

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
            if ($this->socketAccepted = socket_accept($this->socket)) {
                if (is_resource($this->socketAccepted)) {
                    socket_write($this->socketAccepted, ">", 1).chr(0);
                    socket_set_nonblock($this->socketAccepted);
                    echo "New client connected\n";
                    $this->clients[] = $this->socketAccepted;
                }
            }
        }
    }
}

$connectWithClient = new Server($config["address"], $config["port"]);
$connectWithClient->handle();