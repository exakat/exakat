<?php

function &a() {
    return $a;
    return $b->c;
    return $d['e'];
    return A::$f;
}

function &b() {
    return A;
    return \A\B;
    return strtolower($e);
    return A::F;
}


?>