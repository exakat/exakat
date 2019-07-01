<?php

abstract class c {
    function bar() {}
    
    function foo() {
        $this->bar();
    }
}

abstract class c2 {
    function bar() {}
    
    function foo() {
        $this->bar();
        $this->bar2();
    }
}


?>