<?php

// class c is not defined here. 

class aa extends ac {
    function definedMethod() {}
}

class ab extends aa {
     public $x = 2;
     
     function x() {
        parent::undefinedMethod();
        parent::definedMethod();
     }
}

?>