<?php

class x {
    function &fooC($i) : X {}
}

trait t {
    function &fooT($i)  : X {}
    function  fooT2($i) : X {}
    function  &fooT3($i) {}
}

interface i {
    function &fooI($i)  : X;
    function  fooI2($i) : X;
    function  &fooI3($i);
}


function fooF(X $i) {}
function &(X $i) : X {};
function (X $i) use (&$x) {};

?>