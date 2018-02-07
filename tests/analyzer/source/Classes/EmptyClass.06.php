<?php

class NotEmpty {
    function __construct() {}
}

class NotEmpty2 {
    function __clone() {}
}

class ReallyEmpty {
    const X = 1;
}

class ReallyEmpty2 {
    private $p = 2;
}

class ReallyEmpty3 {
    use T;
}

?>