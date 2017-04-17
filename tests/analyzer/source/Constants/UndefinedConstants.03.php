<?php

namespace A;

    define('B', 1);
    define('A\C', 1);

    class E {
        const F = 3;
    }

    print B. ' '. E ."\n";
    print \A\C."\n";

?>