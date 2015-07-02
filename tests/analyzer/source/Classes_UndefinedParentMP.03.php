<?php

class aa extends ac {
    private   function definedMethod() {}
    protected function definedProtectedMethod() {}
    public    function definedPublicMethod() {}
}

class ab extends aa {
     public $x = 2;
     
     function x() {
        parent::undefinedMethod();
        parent::definedPrivateMethod();
        parent::definedPublicMethod();
        parent::definedProtectedMethod();
     }
}

?>