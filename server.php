<?php

require_once "./config.php";

set_time_limit(0);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

$result = socket_bind($socket, $address, $port) or die("Could not bind to socket\n");

$result = socket_listen($socket, 3) or die("Could not set up socket listener\n");

while (true) {
    $spawn = socket_accept($socket) or die("Could not accept incoming connection\n");

    $input = socket_read($spawn, 1024) or die("Could not read input\n");

    $input = trim($input);
    echo "Client Message : ".$input;
}