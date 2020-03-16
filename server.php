<?php

require_once "./config.php";

$marker = true;

set_time_limit(0);

$result = socket_bind($socket, $host, $port) or die("Could not bind to socket\n");

$result = socket_listen($socket, 3) or die("Could not set up socket listener\n");


while ($marker = true) {
    $spawn = socket_accept($socket) or die("Could not accept incoming connection\n");
    
    $input = socket_read($spawn, 1024) or die("Could not read input\n");
    
    $input = trim($input);
    echo "Client Message : ".$input;
    
    $output = strrev($input) . "\n";
    socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
    
    socket_close($spawn);
    socket_close($socket);
    $marker = false;
}