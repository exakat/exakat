<?php

class definedClassA extends definedClassC {
    private $definedProperty;
    protected $definedProtectedProperty;
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