<?php

// class constante used outside the class 

use a as b;

class a {
    public    const APUBLICBUTSBPRIVATE = 1, APUBLICBUTREALLY = 2, APUBLICBUTREALLY2 = 3, APUBLICBUTREALLY3 = 6, APUBLICBUTREALLY4 = 7;
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

class c  {
    function cd() {
        $a = self::APROTECTED;
    }
}

A::APUBLICBUTREALLY2;
D::APUBLICBUTREALLY; // Some other class
$d::APUBLICBUTREALLY4; // Some dynamic class
\a::ASPUBLICBUTREALLY + 3;
b::ASPUBLICBUTREALLY2 + 4;

function b() {
    $a = a::APUBLICBUTREALLY3;
}

?>