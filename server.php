<?php 
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
function onSocketFailure(string $message, $socket = null) {
    if(is_resource($socket)) {
        $message .= ": " . socket_strerror(socket_last_error($socket));
    }
    die($message);
}
if(!is_resource($socket)) {
    onSocketFailure("Failed to create socket")
}
socket_connect($socket, "chat.stackoverflow.com", 6667)
    || onSocketFailure("Failed to connect to chat.stackoverflow.com:6667", $socket);
socket_write($socket, "NICK Alice\r\nUSER alice 0 * :Alice\r\n");
while(true) {
    // read a line from the socket
    $line = socket_read($socket, 1024, PHP_NORMAL_READ);
    if(substr($line, -1) === "\r") {
        // read/skip one byte from the socket
        // we assume that the next byte in the stream must be a \n.
        // this is actually bad in practice; the script is vulnerable to unexpected values
        socket_read($socket, 1, PHP_BINARY_READ);
    }

    $message = parseLine($line);
    if($message->type === "QUIT") break;
}
socket_close($socket);