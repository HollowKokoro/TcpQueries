<?php

require_once "./config.php";

set_time_limit(0);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

socket_bind($socket, $address, $port) or die("Could not bind to socket\n");

socket_listen($socket, 3) or die("Could not set up socket listener\n");

socket_set_nonblock($socket);

$clients = [];

while (true) {
    if ($newSocket = socket_accept($socket)) {
        if (is_resource($newSocket)) {
            socket_set_nonblock($newSocket);
            echo "New client connected\n";
            $clients[] = $newSocket;
        }
    }
    if (count($clients)) {
        foreach ($clients as $k => $v) {
            $string = '';
            if ($char = socket_read($v, 1024)) {
                $string .= $char;
            }
            echo "$k:$string\n";
        }
    }
    sleep(1);
}
socket_close($sock);
