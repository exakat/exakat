<?php

class x {
    const C = 1;
    public $p = 2;
    public static $p2 = 2;
    function bar() : int { return array();}
    static function sbar() : int { return array();}
}

class y {
    private x $x;
    
    function foo() {

        $a = $this->x->bar();
        $b = $a[2];

        $a2 = $this->x::sbar();
        $b = $a2[4];

        $this->x->p =2;
        $this->x::$p2 =2;

        $this->x::C;
    }
}

?>