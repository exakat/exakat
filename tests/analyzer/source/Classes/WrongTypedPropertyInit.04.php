<?php

class x {
    private a $a;
    private ai $ai;
    private b $b;
    private bi $bi;
    private ai $bai;
    private b $c;
    
    function foo() {
        $a = function () : a {};
        $ai = function () : ai {};
        $b = function () : b {};
        $bi = function () : bi {};
        $bai = function () : ai {};
        $c = function () : c {};

        $this->a      = $a();
        $this->ai     = $ai ();
        $this->b      = $b();
        $this->bi     = $bi ();
        $this->bai    = $bai();
        $this->c      = $c();
    }
}

class a implements ai { }

class b extends a implements bi { }

interface ai {}
interface bi {}

?>