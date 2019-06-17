<?php

function foo() {
    static $a = array('a', 'x');
    
    foreach($a as $b) {
    
    }
    
    $c = 1;
    compact('c');
}

?>