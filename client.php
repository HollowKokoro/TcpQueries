<?php
//declare(strict_types = 1);

$config = require_once "./config.php";

class Client
{
    private $socket;

    public function __construct(string $address, int $port)
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n" . socket_strerror(socket_last_error()) . "\n");
        socket_connect($this->socket, $address, $port) or die("Could not connect to server\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
    }

    public function handle()
    {
        while(true) {
            if ($this->nonBlockRead()) {
                $input = readline();
                echo "Input: " . $input . "\n";
            }
            if (socket_select([$this->socket], $write = null, $except = null, 0) > 0) {
                $this->readFromServer();
            }
        usleep(500);
        }
    }

    private function nonBlockRead(): string
    {
        $result = stream_select($read = [STDIN], $write = null, $except = null, 0);
        
        if ($result === false) { 
            throw new RunTimeException("Can not select stream STDIN");
        }

        if ($result === 0) {
            return false;
        }

        $stdinBuffer = "";
        while($stdinData = stream_get_line(STDIN, 1024, "\r\n")) {
            $stdinBuffer .= $stdinData;
        }
        return $stdinData;
    }
    
    private function readFromServer(): string
    {
        $buffer = "";
        while(($read = socket_read($this->socket, 1024, PHP_NORMAL_READ)) !== false) {
            if ($read == "") {
                break;
            }
            $buffer .= $read;
        }
        return $buffer;
    }
}
$connect = new Client($config["address"], $config["port"]);
$connect->handle();