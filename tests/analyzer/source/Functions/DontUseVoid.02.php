<?php

function foo() : void {
    static $x;
}

function () : void {
    global $g;
};

function () : void  {
    $x;
};

$y = foo();