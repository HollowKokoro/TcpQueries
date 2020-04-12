<?php

require_once "./config.php";
require_once "./clientToServer.php";

ini_set('error_reporting', -1);
ini_set('display_errors', 1);

set_time_limit(0);

ob_implicit_flush();

class Server
{
    public $socket;
    public $clients;

    public function openConnection($address, $port)
    {
        $this->clients = [];
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n" . socket_strerror(socket_last_error()) . "\n");
        socket_bind($this->socket, $address, $port) or die("Could not bind to socket\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        socket_listen($this->socket, 3) or die("Could not set up socket listener\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        socket_set_nonblock($this->socket);
    }  
}

$connectWithClient = new ClientToServer();
$connectWithClient->openConnection($address, $port);
$connectWithClient->receive();