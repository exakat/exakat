<?php

function foo(X $x) { 
    $x = y();
}

function foo2(X $x) { 
    $y = $x();
}

function foo3(string $z, $a) { 
    $z = substr($z, 0, 1);
    $a = 1;
}

function foo5(?X $b) { 
    $b = $x();
}

?>