<?php

class a extends c {
     private $x = 1;
     protected $xpr = 1;
     private function y() {}
}

class b extends a {
     private $x = 2;
     protected $xpr = 1;
     private function y() {}
     
     function __construct() {
        a::y();
        b::y();
        c::y();
        d::y();

        a::$x;
        b::$x;
        c::$x;
        d::$x;

        a::$xpr;
        b::$xpr;
        c::$xpr;
        d::$xpr;
     }
}

class c {
     private $x = 1;
     protected $xpr = 1;
     private function y() {}
}

class d {
     private $x = 1;
     protected $xpr = 1;
     private function y() {}
}


$b = new b();
print $b->x;
?>