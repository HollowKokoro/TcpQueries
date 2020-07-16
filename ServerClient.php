<?php

class ServerClient
{
    private $connection;

    public function __construct($socket)
    {
        $this->connection = $socket;
        socket_set_nonblock($this->connection);
    }
/**
 * Читает с клиентского сокета и возвращает переданные клиентом сегменты.
 *
 * @return string|null
 */
    public function tryToReadFromClient(): ?string
    {
        $message = "";
        while ($piece = socket_read($this->connection, 1024, PHP_NORMAL_READ)) {
            if ($piece === false) {
                return null;
            }

            if ($piece === "") {
                break;
            }
            $message .= $piece;
        }
        return $message;
    }
}