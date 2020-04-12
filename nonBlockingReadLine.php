<?php

function nonBlockingReadLineFromStdIn()
{
    $read = array(STDIN);
    $write = array();
    $except = array();
 
    var_dump('Before stream select.');
    $result = stream_select($read, $write, $except, 0);
    var_dump('After stream select.', $result);
 
    if ($result === false) {
        throw new RunTimeException("Can not select stream STDIN");
    }
 
    if ($result === 0) {
        return false;
    }
 
    var_dump('Before get line.');
    $getLine = stream_get_line(STDIN, 1);
    var_dump('After get line.', $getLine);
 
    return $getLine;
}
 
function getPathsFromStdIn()
{
    $content = '';
    while (($line = nonBlockingReadLineFromStdIn()) !== false) {
        $content .= $line;
    }
 
    var_dump('Content', $content);
 
    if (empty($content)) {
        return array();
    }
 
    $lines = explode("\n", rtrim($content));
    return array_map('rtrim', $lines);
}
 
getPathsFromStdIn();