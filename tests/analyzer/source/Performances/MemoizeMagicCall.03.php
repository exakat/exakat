<?php

class x {
    function __get($ax) {
        return $this->a + $this->a + $this->a;
    }
}

class x2 {
    function __set($a, $b) {
        $a = $this->a2;
        $a = $this->a2;
    }
}

class x3 {
    function __get($a) {
        return 1;
    }
    
    function foo() {
        $a = $this->a2;
        $a = $this->a2;
    }

    function bar() {
        $a = $this->a2 + $this->a2;
    }
}

?>