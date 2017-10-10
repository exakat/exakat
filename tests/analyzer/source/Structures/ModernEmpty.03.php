<?php

function foo($x) {
        $for = new stdClass();
        $for->Empty = preg_replace('/ASD/', '', $x[0]);
        if (empty($for->Empty)) {
            error('B', '');
        }
        
        $b = $for->Empty + 1;
}

function foo2($x) {
        $for = new stdClass();
        $for->Empty2 = preg_replace('/ASD/', '', $x[0]);
        if (empty($for->Empty2)) {
            error('A', '');
        }
        
}

?>