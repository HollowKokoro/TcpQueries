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
            $input = readline();
            if ($this->nonBlockRead(STDIN, $input)) {
                echo "Input: " . $input . "\n";
            }
            if (socket_select($input, $write = NULL, $except = NULL, 0) < 1) {
                $this->readInput();
            }     
        }
    }

    private function nonBlockRead ($fd, &$data)
    {
        $read = array($fd);
        $write = array();
        $except = array();
        $result = stream_select($read, $write, $except, 0);
        
        if ($result === false) { 
            throw new RunTimeException("Can not select stream STDIN");
        }

        if ($result === 0) {
            return false;
        }
        
        $data = stream_get_line($fd, 1);
        return $data;
    }
    
    private function readInput()
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