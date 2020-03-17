<?php

require_once "./config.php";

$message = "Hello Server";
echo "Message To server :".$message;

$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");

$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  

socket_close($socket);