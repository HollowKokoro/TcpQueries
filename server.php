<?php

require_once "./config.php";

ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

set_time_limit(0);

ob_implicit_flush();

class Server
{
    public $socket;
    public array $clients;

    public function __construct()
    {
        $this->clients = array();
    }
    
    public function connect($address, $port)
    {
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
        
            if (count($this->clients)) {
                foreach ($this->clients AS $k => $v) {
                    $string = '';
                    if ($char = socket_read($v, 1024)) {
                        $string .= $char;
                    }
                    if ($string) {
                        echo "$k:$string\n";
                    } else {
                        if ($seconds > 60) {
                            if (false === socket_write($v, 'PING')) {
                                socket_close($clients[$k]);
                                unset($clients[$k]);
                            }
                            $seconds = 0;
                        }
                    }
                }
            }     
            $seconds++;
        }
    }    

    /*public function send()
    {
        while($this->receive() != null) {
            socket_write($this->socket, $this->receive(), strlen($this->receive())) or die("Could not send data to server\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        }
    }*/
}

$connect = new Server();
$connect->connect($address, $port);
$connect->receive();
//$connect->send();