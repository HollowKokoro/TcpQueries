<?php

class ServerClient
{
    private $connection;

    public function __construct()
    {
        $this->connection;
        socket_set_nonblock($this->connection);
    }
}
