<?php

class x {
    function usedCMethod() {}
    function unusedCMethod() {}
}

interface xx {
    function unusedIMethod();
    function usedIMethod();
}

$a = new x;
$a->usedCMethod();
$a->usedIMethod();

?>