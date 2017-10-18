<?php

function nonRecursive($x) {
    $y = new Stdclass();
    $y->nonRecursive();
}

function recursive($x) {
    $y = recursive();
}

function nonRecursive2($x) {
    StdClass::nonRecursive2();
}

?>