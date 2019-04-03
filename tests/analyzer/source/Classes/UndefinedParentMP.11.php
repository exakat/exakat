<?php

trait definedClassA {
    protected static $definedProperty;
    protected static $definedProtectedProperty;
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