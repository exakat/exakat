<?php

trait t {
    function foo() {
        echo __TRAIT__;
        echo __METHOD__;
    }
}

function foo() {
    echo __METHOD__;
}

print __TRAIT__;

class ClassName {
    private $x = __METHOD__;
    protected $y = __CLASS__;
    
    function foo() {
        var_dump( $this->x);
        echo __METHOD__;
    }
}

?>