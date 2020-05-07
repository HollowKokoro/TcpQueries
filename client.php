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

    public function handle(): void
    {
        while (true) {
            $userText = $this->tryToReadFromKeyboard();
            if ($userText !== null) {
                echo $userText;
            }

            $this->tryToReadFromServer();
        }
    }

    private function tryToReadFromServer(): void
    {
        $read = [$this->socket];
        $write = null;
        $except = null;
        if (socket_select($read, $write, $except, 0) > 0) {
            $this->readFromServer();
        }
        usleep(50000);
    }

    private function tryToReadFromKeyboard(): ?string
    {
        $read = [STDIN];
        $write = null;
        $except = null;
        $result = stream_select($read, $write, $except, 0);
        
        if ($result === false) { 
            throw new RunTimeException("Can not select stream STDIN");
        }

        if ($result === 0) {
            return null;
        }

        $stdinMessage = "";
        while ($stdinData = stream_get_line(STDIN, 1, "\n")) {
            if ($stdinData == "") {
                break;
            }
            $stdinMessage .= $stdinData;
        }
        return trim($stdinMessage);
    }
    
    private function readFromServer(): string
    {
        $readMessage = "";
        while ($readPart = socket_read($this->socket, 1024, PHP_NORMAL_READ)) {
            if ($readPart == "") {
                break;
            }
            $readMessage .= $readPart;
        }
        return $readMessage;
    }
}

$client = new Client($config["address"], $config["port"]);
$client->handle();