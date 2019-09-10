<?php

use A1 as B;

class P {
    function nonStaticAClass() {}
    static function staticAClass() {}
}

class A1 {
    function f() {
        self::nonStaticButSelfClaSs();
        static::nonStaticButSelfClaSSs();
    }
    
    public function nonStaticButSelfClass() {}
}

class A2 extends A1 {
    function f() {
        self::nonStaticButSelfClass();
        static::nonStaticButSelfClass();
        parent::nonStaticButSelfClass();
        
        P::nonStaticAClass();
        P::staticAClass();

        A1::nonStaticButSelfClass();
        \a1::nonStaticButSelfClass();
        b::nonStaticButSelfClass();
    }
}

class A3 extends A2 {
    function f() {
        self::nonStaticButSelfClasS();
        static::nonStaticButSelfClasS();
        parent::nonStaticButSelfClasS();
    }
}

?>