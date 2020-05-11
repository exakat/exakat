<?php

foo($a);

function foo(A $a) {
    if ($a) {}
    gooA($a);
    gooB($a);
}

function gooA(A|B $a) {
}

function gooB(B|C $a) {
}

?>