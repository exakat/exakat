<?php

    const D = 3;
    const C = 1;
    class x {
        const F = array();
    }

    echo D ? x::f : C;
    
    echo D ? C : C;
    echo D ? \C : C;

    echo D ?: C;
    echo D ?: \C;

    echo D ? foo() : C;
    
    function foo() {}
    
?>
