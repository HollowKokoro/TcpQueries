<?php

$host    = "127.0.0.1";
$port    = 35000;

$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");