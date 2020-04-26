<?php


//прочитать и записать
class ServerClient
{
    private $clients;

    public function __construct()
    {
        $this->userInput = "";
        socket_set_nonblock($newClient);
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