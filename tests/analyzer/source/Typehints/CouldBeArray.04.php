<?php

function foo($f) : array {
    return $f;
}

function boo($b)  {
    return $b;
}

function coo(array $c) : array {
    return $c;
}

function moo(array $m)  {
    return $m;
}

class x {
    private array $p;
    
    function koo()  {
        return $this->p;
    }
}

?>