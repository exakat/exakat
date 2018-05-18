<?php

class x {
    function fooC(X &$i) {}
    function fooC2(X $i) {}
    function __construct(X &$i) {}
    function __get(X $i) {}
}

trait t {
    function fooT(X &$i) {}
    function fooT2(X $i) {}
}

interface i {
    function fooI(X &$i);
    function fooI2(X $i);
}

function fooF(X &$i) {}
function (X &$i) {};


?>