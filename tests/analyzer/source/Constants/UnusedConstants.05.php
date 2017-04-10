<?php

namespace {
    const FOO = 1;  // first
    echo FOO3;
}

namespace A\B {
    const FOO = 2;   // second
    const FOO2 = 3;
    const FOO5 = 5;
}

namespace A\B\C {
    echo FOO;
    echo FOO2;
    echo FOO3;
    echo FOO4;
    echo FOO5;
    
    const FOO4 = 4;
}

namespace A\B\C\D {
    const FOO = 9;   // third
    const FOO2 = 10;
    const FOO5 = 11;
}

?>
