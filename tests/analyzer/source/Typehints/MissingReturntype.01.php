<?php

function fooS() : string  {
    return shell_exec('ls -hla');
}

function fooNone()  {
    return shell_exec('ls -hla');
}

function fooOK() : ?string {
    return shell_exec('ls -hla');
}

?>