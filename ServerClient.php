<?php

class ServerClient
{
    private $connection;

    public function __construct($socket)
    {
        $this->connection = $socket;
        socket_set_nonblock($this->connection);
    }

    public function tryToReadFromClient(): ?string
    {
        $readMessage = "";
        while ($readPart = socket_read($this->connection, 1024, PHP_NORMAL_READ)) {
            if ($readMessage === false) {
                $readMessage = null;
                break;
            }

            if ($readPart == "") {
                break;
            }
            $readMessage .= $readPart;
        }
        return $readMessage;
    }
}