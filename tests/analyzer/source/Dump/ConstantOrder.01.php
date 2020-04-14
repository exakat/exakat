<?php

use const A as F;

const A = 1;
const B = 2 + A;
const C = \D + 2 + A + \A + F + c::C2 + i::I1;
const D = "3";

interface i {
    const I1 = 2;
}

class c {
    const C1 = 2;
    const C2 = self::C1 + 2;
}

?>