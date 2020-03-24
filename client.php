<?php

require_once "./config.php";

$input = readline ();

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

$result = socket_connect($socket, $address, $port) or die("Could not connect to server\n");  

while (true) {
    socket_write($socket, $input, strlen($input)) or die("Could not send data to server\n");
    sleep(1);
}