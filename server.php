<?php

require_once "./config.php";

ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

set_time_limit(0);

ob_implicit_flush();

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n" . socket_strerror(socket_last_error()) . "\n");
socket_bind($socket, $address, $port) or die("Could not bind to socket\n" . socket_strerror(socket_last_error($socket)) . "\n");
socket_listen($socket, 3) or die("Could not set up socket listener\n" . socket_strerror(socket_last_error($socket)) . "\n");
socket_set_nonblock($socket);
$clients = [];

while (true) {
    if ($newSocket = socket_accept($socket)) {
        if (is_resource($newSocket)) {
            socket_write($newSocket, ">", 1).chr(0);
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

            if ($string) {
                echo "$k:$string\n";
            } else {
                if ($seconds > 60) {
                    if (false === socket_write($v, 'PING')) {
                        socket_close($clients[$k]);

                        unset($clients[$k]);
                    }
                    $seconds = 0;
                }
            }
        }
    }

    $seconds++;
}
socket_close($sock);
