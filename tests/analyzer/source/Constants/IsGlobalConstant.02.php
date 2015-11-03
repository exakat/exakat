<?php

namespace {
    const DEFINED_IN_GLOBAL = 1;
    const ABSOLUTE_CONSTANT = 6;
    const DEFINED_IN_A_B = 5;
}


namespace A\B {
    const DEFINED_IN_A_B = 3;
}

namespace X\Y\Z {
    const DEFINED_IN_X = 2;

    echo DEFINED_IN_GLOBAL;
    echo DEFINED_IN_X;        // Not a global constant
    echo UNDEFINED_IN_GLOBAL; // path is absolute
    echo \ABSOLUTE_CONSTANT;  // path is absolute
    echo E_ALL;               // This is a PHP constant
    

    echo A\B\DEFINED_IN_A_B; // This is a namespaced constant : must exists in the full path
}