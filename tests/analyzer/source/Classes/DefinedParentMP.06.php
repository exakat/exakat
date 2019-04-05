<?php

class aparent {
    use t;
}

class b extends aparent {
    function foo() {
        parent::inTrait(parent::$inTraitP);
        parent::inPrivateTrait(parent::$inPrivateTraitP);
    }
}

trait t {
    function inTrait(){}
    private function inPrivateTrait(){}
    
    public $inTraitP = 1;
}

?>