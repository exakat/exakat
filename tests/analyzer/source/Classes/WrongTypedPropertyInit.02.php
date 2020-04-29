<?php

class x {
    private a $a;
    private ai $ai;
    private b $b;
    private bi $bi;
    private ai $bai;
    private b $c;
    
    function foo() {
        $this->a = fooA();
        $this->ai = fooAi();
        $this->b = fooB();
        $this->bi = fooBi();
        $this->bai = fooBai();
        $this->c     = fooC();
    }
}

class a implements ai { }

class b extends a implements bi { }

interface ai {}
interface bi {}

function fooA() : a {}
function fooAi() : ai {}
function fooB() : b {}
function fooBi() : bi {}
function fooBai() : ai {}
function fooC() : c {}

?>