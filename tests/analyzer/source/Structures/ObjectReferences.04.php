<?php

trait t {
    function foo(array &$a, Stdclass &$b, Callable &$c) {}
    
    function foo2(array &$a2 = array(), Stdclass &$b2 = null, \Stdclass &$b3 = null, Callable &$c2 = null) {}
    
    function foo3(&$b2 = null){}
}
?>
