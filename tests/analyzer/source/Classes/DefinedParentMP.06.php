<?php

class a {
    use t;
}

class b extends a {
    function foo() {
        parent::inTrait(parent::$inTraitP);
    }
}

trait t {
    function inTrait(){}
    
    public $inTraitP = 1;
}

?>