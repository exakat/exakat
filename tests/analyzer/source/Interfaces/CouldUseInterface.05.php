<?php

// No methods, no interface
interface i { }

// No methods, no interface
interface i2 { function foo(); }

class AEmpty {}

class AWithFoo {
    function foo() {}
}

?>