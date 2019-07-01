<?php

abstract class uc {
    function bar() {}
}

abstract class uc2 extends uc {
    
    function foo() {
        $this->bar();
    }
}

abstract class uc3 extends uc {
    function foo() {
        $this->bar();
        $this->bar2();
    }
}


?>