<?php

class definedClassA extends definedClassC {
    private static $definedProperty;
    protected static $definedProtectedProperty;
}

class definedClassB extends definedClassA {
     public $x = 2;
     
     function x() {
        parent::$definedPrivateProperty;
        parent::$definedProtectedProperty;
        parent::$undefinedProperty;
     }
}

?>