<?php

class foo {
    private $once = 1;
    private $twice = 1;
    private $three = 1;
    
    function bar() {
        $this->once;
        $this->twice;
        $this->twice;

        $this->three;
        $this->three;
        $this->undefined = $this->undefined2 + 2;
        $this->undefined = $this->undefined2 + 2;
    }

    function bar2() {
        $this->three = $this->undefined2;
    }

    function bar3() {
        $this->three;
    }
}

?>