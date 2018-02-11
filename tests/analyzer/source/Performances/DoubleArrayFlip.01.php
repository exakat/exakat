<?php

function foo() {
    $a = array_flip($b);
    unset($a['c']);
    $c = array_flip($a);
}

function foo2() {
    $a2 = array_flip($b);
    unset($a['c']);
    $d++;
    $f = 3 + 3;
    $c = array_flip($a2);
}

function foo3() {
    $a3 = array_flip($b);
    unset($a['c']);
    $d++;
    $f = 3 + 3;
    $c = array_flip($b);
}

function foo4() {
    $a4 = array_flip($b);
}

?>