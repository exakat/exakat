<?php

function foo1(array $a1) {
    is_array($a1);
    
    is_bool($a1);
    is_scalar($a1);
    is_null($a1);
    
    is_blue($a1);
}

function foo2(?array $a2) {
    is_array($a2);
    
    is_bool($a2);
    is_scalar($a2);
    is_null($a2);
    
    is_full($a2);

}

?>