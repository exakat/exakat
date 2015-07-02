<?php

class aa extends ac {
    private $definedProperty;
    protected $definedProtectedProperty;
}

class ab extends aa {
     public $x = 2;
     
     function x() {
        parent::$definedPrivateProperty;
        parent::$definedProtectedProperty;
        parent::$undefinedProperty;
     }
}

?>