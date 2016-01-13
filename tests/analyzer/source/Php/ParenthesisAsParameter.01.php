<?php
function getArray() {
    return [1, 2, 3];
}

function squareArray(array &$a) {
    foreach ($a as &$v) {
        $v **= 2;
    }
}

// Generates a warning in PHP 7.
squareArray((getArray()));
squareArray((getArray()), (f()));
squareArray((getArray()), (f()), (f2()));

(1) + (strtolower($x)); 

(strtoupper($x));
?>