<?php

function foo($x) {
        $for = new stdClass();
        A::$Empty = preg_replace('/ASD/', '', $x[0]);
        if (empty(A::$Empty)) {
            error('B', '');
        }
        
        $b = A::$Empty + 1;
}

function foo2($x) {
        $for = new stdClass();
        A::$Empty2 = preg_replace('/ASD/', '', $x[0]);
        if (empty(A::$Empty2)) {
            error('A', '');
        }
        
}

?>