<?php

class x {
    private a $a;
    private ai $ai;
    private b $b;
    private bi $bi;
    private ai $bai;
    private b $c;
    
    function foo() {
        $this->a = new a;
        $this->ai = new a();
        $this->b = new b;
        $this->bi = new b;
        $this->bai = new b();
        $this->c     = new c();
    }
}

class a implements ai { }

class b extends a implements bi { }

interface ai {}
interface bi {}

?>