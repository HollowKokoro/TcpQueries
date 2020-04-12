<?php

require_once "./server.php";

class ClientToServer extends Server
{
    public function receive()
    {
        while (true) {
            if ($socketAccepted = socket_accept($this->socket)) {
                if (is_resource($socketAccepted)) {
                    socket_write($socketAccepted, ">", 1).chr(0);
                    socket_set_nonblock($socketAccepted);
                    echo "New client connected\n";
                    $this->clients[] = $socketAccepted;
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