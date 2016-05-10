<?php 

class A {
    function do() {}
}

class B {
    private $a = null;
    
    function __construct() {
        $this->a = new A();
    }
    
    function x() {
        $this->a->do();
    }
}

$a = new A();
class C {
    function x() {
        global $aInC;
        
        $aInC->do();
    }
}

class COK {
    function __construct() {
        global $aInCOK;
        
        $aInC->do();
    }
}

class D {
    function x() {
        $GLOBALS['aInD']->do();
    }
}

class DOK {
    function __construct() {
        $GLOBALS['aInDOK']->do();
    }
}

?>