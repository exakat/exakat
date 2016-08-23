<?php

trait definedClassA {
    private $definedProperty;
    protected $definedProtectedProperty;
}

class definedClassB extends definedClassA {
    use definedClassA;
    
     public $x = 2;
     
     function x() {
        parent::$definedPrivateProperty;
        parent::$definedProtectedProperty;
        parent::$undefinedProperty;
        normal::$definedPrivateProperty;
     }
}

?>