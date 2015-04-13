<?php

class a extends c {
    private   function definedMethod() {}
    protected function definedProtectedMethod() {}
    public    function definedPublicMethod() {}
}

class b extends a {
     public $x = 2;
     
     function x() {
        parent::undefinedMethod();
        parent::definedPrivateMethod();
        parent::definedPublicMethod();
        parent::definedProtectedMethod();
     }
}

?>