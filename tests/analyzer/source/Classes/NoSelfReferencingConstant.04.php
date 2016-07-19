<?php

const X = 1;

interface i {
    // Don't know how to do that with an interface...
    const II = self::II;
    const II2 = self::II + 1;

    const I3 = i::I3;
    const I4 = \i::I4;

    const I32 = i::I32 . 3;
    const I42 = \i::I42 * 2;

    const I45 = '1';
}

class x implements i  {}

new x();
?>