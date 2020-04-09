<?php

require_once "./config.php";

ini_set('error_reporting', -1);
ini_set('display_errors', 1);

set_time_limit(0);

ob_implicit_flush();

class Server
{
    public $socket;
    public $clients;

    public function __construct($address, $port)
    {
        $this->clients = [];
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n" . socket_strerror(socket_last_error()) . "\n");
        socket_bind($this->socket, $address, $port) or die("Could not bind to socket\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        socket_listen($this->socket, 3) or die("Could not set up socket listener\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        socket_set_nonblock($this->socket);
    }
    
    public function receive()
    {
        while (true) {
            // Accept new connections
            if ($newsock = socket_accept($this->socket)) {
                if (is_resource($newsock)) {
                    socket_write($newsock, ">", 1).chr(0);
                    socket_set_nonblock($newsock);
                    echo "New client connected\n";
                    $this->clients[] = $newsock;
                }
            }
        
            foreach ($this->clients as $k => $v) {
                $string = '';
                if ($char = socket_read($v, 1024)) {
                    $string .= $char;
                }
                if ($string) {
                    echo "$k:$string\n";
                } 
            }
        }
    }    
}

$connect = new Server($address, $port);
$connect->receive();
