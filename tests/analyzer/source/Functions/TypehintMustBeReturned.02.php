<?php

interface i {
    function foo() : array; 
}

abstract class j {
    abstract function foo() : array; 
}

trait t {
    static function foo() : array {  } 
}

?>