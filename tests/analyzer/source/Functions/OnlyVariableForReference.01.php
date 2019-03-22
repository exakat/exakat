<?php

// For testing purpose, this must be before the definition
foo($a, $b);
foo($a, $b[1]);
foo($a, $b->d);
foo(1, 2);
foo(1, C);
foo(1, array());
foo(1, x());

function foo($a, &$b) {
    
}

function x() {}

?>