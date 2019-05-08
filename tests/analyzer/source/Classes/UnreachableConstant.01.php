<?php

class x {
    private const A = 1;
    
    function foo() {
        echo x::A;
        echo Self::A;
        echo Static::A;
        echo \x::A;

        echo Z::A; // Just unknown
    }
}

class y extends x {
    function foo() {
        echo x::A;
        echo sElf::A;
        echo sTatic::A;
        echo \x::A;
        echo pArent::A;

        echo Z::A; // Just unknown
    }
}

echo x::A;
echo \x::A;

// Not useful : this is a problem of outside a class
//echo self::A;
//echo static::A;
//echo parent::A;

echo Z::A; // Just unknown

?>