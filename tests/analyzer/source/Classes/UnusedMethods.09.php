<?php

class x {
    function foo() {
        $this->$m();
    }

    function bar() {
    }
}

class x2 {
    function foo() {
        $this->foo();
    }

    function bar2() {
    }
}

?>