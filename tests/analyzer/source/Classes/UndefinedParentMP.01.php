<?php

class definedClassA extends definedClassC {
    function definedMethod() {}
}

class definedClassB extends definedClassA {
     public $x = 2;
     
     function x() {
        parent::undefinedMethod();
        parent::definedMethod();
        parent::class;
     }
}

?>