<?php

class x {
    private a $a;
    private ai $ai;
    private b $b;
    private bi $bi;
    private ai $bai;
    private b $c;
    
    function foo() {
        $this->a     = y::fooA();
        $this->ai    = y::fooAi();
        $this->b     = y::fooB();
        $this->bi    = y::fooBi();
        $this->bai   = y::fooBai();
        $this->c     = y::fooC();
    }
}

class a implements ai { }

class b extends a implements bi { }

interface ai {}
interface bi {}

class y {
    static function fooA() : a {}
    static function fooAi() : ai {}
    static function fooB() : b {}
    static function fooBi() : bi {}
    static function fooBai() : ai {}
    static function fooC() : c {}
}
?>