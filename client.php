<?php

require_once "./config.php";

$message = "Hello Server";
echo "Message To server :".$message;

$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  

socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");

$result = socket_read ($socket, 1024) or die("Could not read server response\n");
echo "Reply From Server  :".$result;

socket_close($socket);