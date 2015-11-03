<?php

function nonRecursive($x) {
    $y = new Stdclass();
    $y->nonRecursive();
}

function nonRecursive2($x) {
    StdClass::nonRecursive2();
}

?>