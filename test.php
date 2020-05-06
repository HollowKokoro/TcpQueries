<?php

class test
{
    private $cashInput;

    public function __construct()
    {
        $this->cashInput = "";
    }

    public function tryReadFromKeyboard(): ?string
    {
        while (true) {
            $input = readline();
            $this->cashInput .= $input;
        }
        return $this->cashInput;
    }

}
    $example = new Test(); 
    $example->tryReadFromKeyboard();