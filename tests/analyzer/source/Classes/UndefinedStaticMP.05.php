<?php

// members are protected but not static
class a extends c {
     private $x = 1;
     
     function definedInParent() { print __METHOD__."\n";}
     protected $pdefinedInParent;
     public static $publicdefinedInParent = 3;
}

class b extends a {
     public $x = 2;
     
     function y() {
        static::definedInParent();
        static::definedInParentParent();
        static::undefined();
        static::definedinStatic();

        static::$pdefinedInParent;
        static::$publicdefinedInParent;
        static::$pdefinedInParentParent;
        static::$pundefined;
        static::$pdefinedinStatic;
     }
     
     function definedinStatic() { print __METHOD__."\n";}
     protected $pdefinedinStatic;
}

class c {
     private $x = 1;

     function definedInParentParent() { print __METHOD__."\n";}
     protected $pdefinedInParentParent;
}


$b = new b();
//print a::$x
print $b->y();
?>