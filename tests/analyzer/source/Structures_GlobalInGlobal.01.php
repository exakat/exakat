<?php

$global = $global + $global2;

function f() {
    $functionVar = 2;
}

function ($x) {
    $closureVar = 2;
};

class x {
    private $classVar;
    
    function y($x) {
        $methodVar = 2;
    }
}

interface i {
    function t($interfaceVar);
}

trait t {
    private $traitVar;
    
    function t($x) {
        $traitMethodVar = 2;
    }
}
