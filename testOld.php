<?php

function tryReadFromKeyboard(): ?string
{
    $read = [STDIN];
    $write = null;
    $except = null;
    $result = stream_select($read, $write, $except, 0);
    if ($result === false) {
        throw new RunTimeException("Can not select stream STDIN");
    }
    if ($result === 0) {
        return null;
    }
    $stdinBuffer = "";
    while ($stdinData = stream_get_line(STDIN, 1024, "\n")) {
        if ($stdinData == "") {
            break;
        }
        $stdinBuffer .= $stdinData;
    }
    return trim($stdinBuffer);
}

while (true) {
    $userText = tryReadFromKeyboard();
    if ($userText !== null) {
        echo $userText;
    }
}