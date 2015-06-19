<?php

namespace {
    $x = pow(1, 2);
    $y = \Pow(POW(3, 4), 5);
}

namespace a {
    // no redefinition
    $xa = pOw(6, 7);
    $ya = \POw(pOW(8, 9), 10);
}

namespace b {
    // with redefinition
    function pow($a, $b) { print __FUNCTION__." $a \n";}
    
    $xa = pow(11, 12);
    $ya = \POW(pOw(13, 14), 15);
}

?>