<?php

class x {
    function overwrittenMethod() {}
    function overwrittenMethod1() {}
    function notOverwrittenMethod() {}
    function usedBelow() {
        $this->usedAbove();
    }
}

class xx extends x {
    function overwrittenMethod() {}
    function overwrittenMethod1() {}
    function usedAbove() {}
    
    function foo() {
        $this->overwrittenMethod();
        $this->usedBelow();
        $this->foo();
    }
}

?>