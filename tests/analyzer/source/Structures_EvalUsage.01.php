<?php

namespace {
    eval('$y = 1;');
    create_function('$y', '$y = 2;');
    EVAL('$y = 3;');
}

namespace B{
    eval('$y = 4;');
    var_dump($y);
    create_function('$y', '$y = 5;');

    function create_function($a, $b) {
        print __METHOD__."\n";
    }
}

?>