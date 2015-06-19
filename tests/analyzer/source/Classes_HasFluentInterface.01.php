<?php

class fluent {
    public $y = 2;
    
    function __get($name) {}
    
    function fluent() { return $this;}
    
    function nonFluent() { return '$this'; }
}

class Notfluent {
    function nonFluent() { return $that;}
    
    function nonFluent2() { return '$this'; }
}

?>