<?php

trait t {
    function bar() {}
    
    function foo() {
        $this->bar();
    }
}

trait t2 {
    function bar() {}
    
    function foo() {
        $this->bar();
        $this->bar2();
    }
}


?>