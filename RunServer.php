<?php
declare(strict_types = 1);

$config = require_once "./config.php";
require_once "./ServerClient.php";
require_once "./Server.php";

$runServer = new Server($config["address"], $config["port"]);
$runServer->handle();