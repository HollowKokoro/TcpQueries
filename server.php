<?php

require_once "./config.php";

set_time_limit(0);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

socket_bind($socket, $address, $port) or die("Could not bind to socket\n");

socket_listen($socket, 3) or die("Could not set up socket listener\n");

socket_set_nonblock($socket);

$clients = [];

// Loop continuously
while (true) {
    // Accept new connections
    if ($newSocket = socket_accept($socket)) {
        if (is_resource($newSocket)) {
            // Don't block new connection
            socket_set_nonblock($newSocket);
            // Do something on the server side
            echo "New client connected\n";
            // Append the new connection to the clients array
            $clients[] = $newSocket;
        }
    }
}