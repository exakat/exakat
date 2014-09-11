<?php

class a extends c {
    private function definedMethod() {}
}

class b extends a {
     public $x = 2;
     
     function x() {
        parent::undefinedMethod();
        parent::definedMethod();
     }
}

?>