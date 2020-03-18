<?php

require_once "./config.php";

$message = "Hello Server";
echo "Message To server :".$message;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");

$result = socket_connect($socket, $address, $port) or die("Could not connect to server\n");  

while (true) {
    socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
}