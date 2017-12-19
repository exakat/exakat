<?php

interface i {}
interface i2 {}
interface i3 {}
interface i4 {}
interface i5 {}

function foo(i $i, stdclass $x) {}
function (i5 $i, stdclass $x) {};

class y {
    function foo(i2 $i, stdclass $x) {}
}

interface j {
    function foo(i3 $i, stdclass $x);
}

trait t {
    function foo(i4 $i, stdclass $x) {}
}



?>