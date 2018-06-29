<?php

class definedClassA extends definedClassC {
    private   $definedPrivateProperty;
    protected $definedProtectedProperty;
    public    $definedPublicProperty;
}

class definedClassB extends definedClassA {
     public $x = 2;
     
     function x() {
        parent::$definedPrivateProperty;
        parent::$definedProtectedProperty;
        parent::$definedPublicProperty;
        parent::$undefinedProperty;
     }
}

?>