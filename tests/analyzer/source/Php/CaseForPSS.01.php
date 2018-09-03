<?php
class A {
    public function a () { print __METHOD__."\n"; }
}

class B extends A {
    public function a() { parent::a(); print __METHOD__."\n"; }
    public function AUC() { PARENT::a(); print __METHOD__."\n"; }
    public function AUS() { Self::a();  }
    public function AUS2() { self::a();  }
//    public function AUST() { new STATIC::a(); } can't test for static... 
}

$x = new B();
$x->a();
$x->AUC();

?>