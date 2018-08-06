<?php

class x {
    function foo() { return $a ?? $this; }
    
    function &foo1() { return $a ?? $this->b; }
    
    function &foo2() { return $a ? $this->c : $b; }
    
    function &foo3() { return $a ?: $this; }
}
?>