<?php

class ServerClient
{
    public $connection;

    public function __construct()
    {
        socket_set_nonblock();
    } 
}