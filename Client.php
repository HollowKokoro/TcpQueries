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

    private function tryToReadFromServer(): ?string
    {
        $read = [$this->socket];
        $write = null;
        $except = null;
        if (socket_select($read, $write, $except, 0) > 0) {
            $readMessage = "";
            while ($readPart = socket_read($this->socket, 1024, PHP_NORMAL_READ)) {
                if ($readPart == "") {
                    break;
                }
                $readMessage .= $readPart;
                usleep(50000);
            }
            return $readMessage;
        }
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
            usleep(50000);
        }
        return trim($stdinMessage);
    }
}

$client = new Client($config["address"], $config["port"]);
$client->handle();