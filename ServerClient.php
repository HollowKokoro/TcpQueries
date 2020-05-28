<?php

class ServerClient
{
    private $connection;

    public function __construct($socket)
    {
        $this->connection = $socket;
        socket_set_nonblock($this->connection);
    }
}
