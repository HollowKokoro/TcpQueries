<?php

require_once "./config.php";

$address = '192.168.1.53';
$port = 10000;

$message = "Hello Server";
echo "Message To server :".$message;

$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");

$result = socket_connect($socket, $address, $port) or die("Could not connect to server\n");  

socket_close($socket);