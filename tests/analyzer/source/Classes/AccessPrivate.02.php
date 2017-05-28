<?php

class a extends c {
     private $x = 1;
     private function y() {}

     public $xp = 1;
     protected function yp() {}
}

class b extends a {
     private $x = 2;
     private function y() {}
     
     function __construct() {
        a::y();
        parent::y();

        a::$x;
        parent::$x;

        a::yp();
        parent::yp();

        a::$xp;
        parent::$xp;
     }
}

class c {
     private $x = 1;
     private function y() {}
}

class d {
     private $x = 1;
     private function y() {}

     private $xp = 1;
     private function yp() {}
}


$b = new b();
print $b->x;
print $b->xp;
?>