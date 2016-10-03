<?php

class Foo { 
    function __destruct() {
        throw new Exception('__destruct');
    }
}

class Bar { 
    function __construct() {
        throw new Exception('__construct');
    }
    
    function __destruct() {
        $a++;
    }
}

class Bar2 { 
    function __construct() {
        throw new Exception('__construct');
    }
}

$foo = new Foo();
unset($foo);

?>
