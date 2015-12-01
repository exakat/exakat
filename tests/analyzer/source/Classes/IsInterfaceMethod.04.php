<?php

interface i {
    function mi($mi1, $mi2, $mi3);
}

interface j extends i {
    function mj($mj1, $mj2, $mj3);
}

class b {
    function mb($mi1, $mi2, $mi3) {
        echo __METHOD__."\n";
    }
}

class c extends b implements j, \ArrayAccess {
    // defined in Parent
    function mb($mi1, $mi2, $mi3) {
        echo __METHOD__."\n";
    }

    // Not defined in an interface 
    function mh($mi1, $mi2, $mi3) {
        echo __METHOD__."\n";
    }

    function mi($mi1, $mi2, $mi3) {
        echo __METHOD__."\n";
    }

    function mj($mi1, $mi2, $mi3) {
        echo __METHOD__."\n";
    }

public function offsetExists ( $offset ) {}
public function offsetGet ( $offset ) {}
public function offsetSet ( $offset , $value ) {}
public function offsetUnset ( $offset ) {}
}

$c = new c();
$c->mi();
?>