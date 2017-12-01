<?php

class fluentClass {
    public $y = 2;
    
    function __get($name) {}
    
    function fluent() { return $this;}
    
    function nonFluent1() { return '$this'; }
}

class NotfluentClass {
    function nonFluent2() { return $that;}
    
    function nonFluent3() { return '$this'; }
}

function functionCantBeFluent() { return $this; }

?>