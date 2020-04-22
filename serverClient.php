<?php

require_once "./server.php";

class ServerClient
{
    private $userInput;
    private $clients;

    public function __construct()
    {
        $this->userInput = "";
        $this->clients[] = "clientId";
    }
 
    public function send()
    {
        while (!empty($this->userInput)) {
            socket_write($this->clients, $this->userInput, strlen($this->userInput)) or die("Could not send data to server\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
            sleep(1);
        }
    }
}