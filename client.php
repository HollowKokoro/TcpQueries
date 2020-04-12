<?php

require_once "./config.php";

class Client
{
    public $socket;

    public function __construct($address, $port)
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n" . socket_strerror(socket_last_error()) . "\n");
        socket_connect($this->socket, $address, $port) or die("Could not connect to server\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
    }

    public function sendInput()
    {
        while (true) {
            $input = readline();
            $input .= "\n";
            socket_write($this->socket, $input, strlen($input)) or die("Could not send data to server\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
            sleep(1);
        }
    }
}
$connect = new Client($address, $port);
$connect->sendInput();