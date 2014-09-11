<?php

class a extends c {
    function definedMethod() {}
}

class b extends a {
     public $x = 2;
     
     function x() {
        parent::undefinedMethod();
        parent::definedMethod();
     }
}

?>