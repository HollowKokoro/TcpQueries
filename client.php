<?php

$config = require_once "./config.php";
require_once "./nonBlockingReadLine.php";

class Client
{
    private $socket;

    public function __construct($address, $port)
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n" . socket_strerror(socket_last_error()) . "\n");
        socket_connect($this->socket, $address, $port) or die("Could not connect to server\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
    }

    public function handle()
    {
        while(true) {
            if (is_string(nonBlockingReadLineFromStdIn())) {
                socket_write($this->socket, 1024, strlen($this->socket));
            }
            if (socket_select($read, $write = NULL, $except = NULL, 0) < 1) {
                $this->readInput();
            }
        }
        /*while (true) {
            $input = readline();
            $input .= "\n";
            socket_write($this->socket, $input, strlen($input)) or die("Could not send data to server\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        }*/
    }
    
    public function readInput()
    {
        while(($read = socket_read($this->socket, 1024, PHP_NORMAL_READ)) !== false) {
            $str .= $read;
            if (strpos($str, "\n") !== false) {
                break;
            }
        }
    }
}
$connect = new Client($config["address"], $config["port"]);
$connect->handle();
$connect->readInput();