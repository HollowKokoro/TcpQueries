<?php

require_once "./server.php";

class ClientToServer extends Server
{
    public $userInput;
    public $socketAccepted;

    public function __construct()
    {
        $this->userInput = "";
        $this->socketAccepted = null;
    }

    public function receive()
    {
        while (true) {
            if ($this->socketAccepted = socket_accept($this->socket)) {
                if (is_resource($this->socketAccepted)) {
                    socket_write($this->socketAccepted, ">", 1).chr(0);
                    socket_set_nonblock($this->socketAccepted);
                    echo "New client connected\n";
                    $this->clients[] = $this->socketAccepted;
                }
            }
        
            foreach ($this->clients as $k => $v) {
                $string = '';
                if ($char = socket_read($v, 1024)) {
                    $string .= $char;
                }
                if ($string) {
                    $string = "$k:$string\n";
                    echo $string;
                    $this->userInput = $string;
                } 
            }
        }
    }
    
    public function send()
    {
        while (!empty($this->userInput)) {
            socket_write($this->clients, $this->userInput, strlen($this->userInput)) or die("Could not send data to server\n" . socket_strerror(socket_last_error($this->socket)) . "\n");
        }
    }
}