<?php

class a extends c {
    private $definedProperty;
}

class b extends a {
     public $x = 2;
     
     function x() {
        parent::$definedProperty;
        parent::$undefinedProperty;
     }
}

?>