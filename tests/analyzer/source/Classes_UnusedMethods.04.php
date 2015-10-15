<?php

class x {
    function usedCMethod() {}
    function unusedCMethod() {}
}

interface xx {
    function unusedIMethod();
    function usedIMethod();
}

$a->usedCMethod();
$a->usedIMethod();

?>