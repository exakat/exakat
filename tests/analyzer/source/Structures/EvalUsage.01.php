<?php

namespace {
    eval('$y = 1;');
    create_function('$y', '$y = 2;');
    EVAL('$y = 3;');
}

namespace B{
    eval('$y = 4;');
    var_dump($y);
    
    // OK as a method
    $a->create_function('$z', '$y = 6;');

    // OK as a locally defined function
    function create_function($a, $b) {
        print __METHOD__."\n";
    }
    create_function('$y', '$y = 5;');
}

?>