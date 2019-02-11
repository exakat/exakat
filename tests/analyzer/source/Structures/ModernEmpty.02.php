<?php

function foo($x) {
        $forEmpty = preg_replace('/ASD/', '', $x[0]);
        if (empty($forEmpty)) {
            error('B', '');
        }
        
        $b = $forEmpty + 1;
}

function foo2($x) {
        $forEmpty2 = preg_replace('/ASD/', '', $x[0]);
        if (!empty($forEmpty2)) {
            error('A', '');
        }
}

function foo3($x) {
        $forEmpty3 = OK;
        if (!empty($forEmpty3)) {
            error('A', '');
        }
}

?>