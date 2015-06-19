<?php

class a extends c {
    private $definedProperty;
    protected $definedProtectedProperty;
}

class b extends a {
     public $x = 2;
     
     function x() {
        parent::$definedPrivateProperty;
        parent::$definedProtectedProperty;
        parent::$undefinedProperty;
     }
}

?>