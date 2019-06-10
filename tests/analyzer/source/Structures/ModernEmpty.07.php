<?php

class x {
    function foo() {
        // PHP 5.5+ empty() usage
        $this->d = strtolower($b0 . $c0);
        if (empty(strtolower($b0 . $c0))) {
            doSomethingWithoutA();
        }
    }
    
    function foo2() {
        // Compatible empty() usage
        $this->a = strtolower($b . $c);
        if (empty($this->a)) {
            doSomethingWithoutA();
        }
    }
    
    function foo3() {
        // $a is reused
        $this->a2 = strtolower($b . $c);
        if (empty($this->a2)) {
            doSomethingWithoutA();
        } else {
            echo $this->a2;
        }
    }
}
?>