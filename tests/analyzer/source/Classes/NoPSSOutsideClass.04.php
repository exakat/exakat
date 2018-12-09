<?php

class x {
    function foo(parent $x, grandparent $y) {}
    function foo2() : parent {}
    function __get(parent $x) : parent {}
    function __set(parent $x, parent $y) {}
}

class x{}

class y extends z {
    function foo3(parent $x, grandparent $y) {}
    function foo4() : parent {}
    function __get(parent $x2) : parent {}
    function __set(parent $x2, parent $y) {}
}
?>