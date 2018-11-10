<?php

trait t1 {
    function foo() {
        $this->foo();
    }
}

trait t2 {
    function foo() {
        $this->bah();
    }
}

trait t31 {
    use t32;
    
    function foo() {
        $this->bah();
    }
}

trait t32 {
    function bah() {
    }
}

?>