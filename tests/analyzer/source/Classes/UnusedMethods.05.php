<?php

class x {
    function usedCMethod() {}
    function unusedCMethod() {}
}

class t {
    function usedTMethod() {}
    function unusedTMethod() {}
}

interface xx {
    function unusedIMethod();
    function usedIMethod();
}

$a = new x;
$a->usedCMethod();
(new t)->usedTMethod();
$a->usedIMethod();

?>