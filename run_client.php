<?php
declare(strict_types = 1);

$config = require_once "./config.php";
require_once "./Client.php";

$runClient = new Client($config["address"], $config["port"]);
$runClient->handle();