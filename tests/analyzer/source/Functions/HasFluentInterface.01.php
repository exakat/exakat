<?php

class fluent {
    public $y = 2;
    
    function __get($name) {}
    
    function fluent() { return $this;}
    
    function nonFluent1() { return '$this'; }
}

class Notfluent {
    function nonFluent2() { return $that;}
    
    function nonFluent3() { return '$this'; }
}

function functionCantBeFluent() { return $this; }

?>