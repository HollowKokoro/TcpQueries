<?php

require_once "./config.php";

set_time_limit(0);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");
socket_bind($socket, $address, $port) or die("Could not bind to socket\n");
socket_listen($socket, 3) or die("Could not set up socket listener\n");

$clients = [];

while (true) {
    $newSocket = socket_accept($socket) or die("Could not accept incoming connection\n");
    if (is_resource($newSocket)) {
        echo "New client connected\n";
        $clients[] = $newSocket;
        while(($read = socket_read($newSocket, 1024, PHP_NORMAL_READ)) !== false) {
            echo "Read: $read\n";
        }
    }
}