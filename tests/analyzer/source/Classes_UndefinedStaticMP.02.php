<?php

class a extends c {
     private $x = 1;
     
     function definedInParent() { print __METHOD__."\n";}
     private $pdefinedInParent;
}

class b extends a {
     public $x = 2;
     
     function y() {
        SELF::definedInParent();
        SELF::definedInParentParent();
        SELF::undefined();
        SELF::definedinStatic();

        SELF::$pdefinedInParent;
        SELF::$pdefinedInParentParent;
        SELF::$pundefined;
        SELF::$pdefinedinStatic;
     }
     
     function definedinStatic() { print __METHOD__."\n";}
     private $pdefinedinStatic;
}

class c {
     private $x = 1;

     function definedInParentParent() { print __METHOD__."\n";}
     private $pdefinedInParentParent;
}


$b = new b();
//print a::$x
print $b->y();
?>