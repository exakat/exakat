<?php

namespace {
    define('\\FOO', 1);
    echo FOO3;
}

namespace A\B {
    define('\\A\\B\\FOO', 2);
    define('\\A\\B\\FOO2', 3);
    define('\\A\\B\\FOO5', 5);
}

namespace A\B\C {
    echo FOO;
    echo FOO2;
    echo FOO3;
    echo FOO4;
    echo FOO5;
    
    define('\\A\\B\\C\\FOO4', 4);
}

namespace A\B\C\D {
    define('\\A\\B\\C\\D\\FOO', 9);
    define('\\A\\B\\C\\D\\FOO2', 10);
    define('\\A\\B\\C\\D\\FOO5', 11);
    define('A\\B\\C\\D\\FOO6', 12);
}

?>
