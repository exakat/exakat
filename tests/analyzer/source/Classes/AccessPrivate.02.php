<?php

class a extends c {
     private $x = 1;
     private function y() {}
}

class b extends a {
     private $x = 2;
     private function y() {}
     
     function __construct() {
        a::y();
        parent::y();

        a::$x;
        parent::$x;
     }
}

class c {
     private $x = 1;
     private function y() {}
}

class d {
     private $x = 1;
     private function y() {}
}


$b = new b();
print $b->x;
?>