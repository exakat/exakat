<?php

function foo() {
    $string[3] = 2;
    $array["b"] = 3;
    $array["b"] = 3;
    $string[3] = 2;
    
    $c++;
    $c++;
    
    if ($debug) { $d++; }
    if ($debug) { $d++; }
}

function foo2() {

}

?>