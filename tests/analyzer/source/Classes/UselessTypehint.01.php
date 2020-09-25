<?php

function __get(string $name) {}

class x {

    function __construct(string $name) {}
    function __set(string $name1, $x) {}
    function __get(string $name2) {}
}

class y {
    function __set($name3, array $x) {}
    function __get($name4) {}
}

class z {

    function __set(?string $name5, $x) {}
    function __get(?string $name6) {}
}

?>