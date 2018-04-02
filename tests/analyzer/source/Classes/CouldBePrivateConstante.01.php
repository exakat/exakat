<?php

// class constante used outside the class 

use a as b;

class a {
    public    const APUBLICBUTSBPRIVATE = 1, APUBLICBUTSBPRIVATE2 = 2, APUBLICBUTREALLY2 = 3, APUBLICBUTREALLY3 = 6, APUBLICBUTREALLY4 = 7, APUBLICBUTREALLY5 = 10, APUBLICBUTREALLY6 = 11 ;
    protected const APROTECTED = 4, APROTECTESBPRIVATE = 8;
    private   const APRIVATE = 5;

    function b() {
        $a = self::APUBLICBUTSBPRIVATE + self::APROTECTED + self::APRIVATE;
        $b += a::APUBLICBUTREALLY;
        
        $c = self::ASPUBLICBUTSBPRIVATESELF + 1;
        $d = static::ASPUBLICBUTSBPRIVATESTATIC + 2;
        $e = \a::ASPUBLICBUTSBPRIVATEFULL + 3;
        $f = self::APROTECTESBPRIVATE;
    }
}

class c extends a {
    private   const APRIVATE = 5;

    function cd($a = a::APROTECTED) {
        $a = self::APROTECTED + self::APRIVATE;
    }
}

D::APUBLICBUTREALLY6; // Some other class
b::APUBLICBUTREALLY2 + 4;
A::APUBLICBUTREALLY5;
$d::APUBLICBUTREALLY4; // Some dynamic class
\a::ASPUBLICBUTREALLY + 3;

function b() {
    $a = a::APUBLICBUTREALLY3;
}

?>