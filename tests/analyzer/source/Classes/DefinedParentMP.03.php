<?php

class definedClassA extends definedClassC {
    private   function definedMethod() {}
    protected function definedProtectedMethod() {}
    public    function definedPublicMethod() {}
}

class definedClassB extends definedClassA {
     public $x = 2;
     
     function x() {
        parent::undefinedMethod();
        parent::definedPrivateMethod();
        parent::definedPublicMethod();
        parent::definedProtectedMethod();
     }
}

?>