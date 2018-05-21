<?php

abstract class x {
    function usedCMethod() {}
    abstract function abstractCMethod();
    function unusedCMethod() {}
}

class xx extends x {
    function abstractCMethod() {}
    function unusedCMethod() {}
}

$a->usedCMethod();
$a->usedTMethod();
$a->usedIMethod();

?>