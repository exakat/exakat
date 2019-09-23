<?php
class x {
    const NOT_SET = 1;

    private $p1 = null;
    private $p2;
    private $p3 = self::NOT_SET;
    private $p4 = 4;
    private $p5 = "d";
    
    function foo1() : C { return $this->p1;}
    function foo2() : C { return $this->p2;}
    function foo3() : C { return $this->p3;}
    function foo4() : C { return $this->p4;}
    function foo5() : string { return $this->p5;}
    function foo6() : C { return $this->p6;}
}
?>