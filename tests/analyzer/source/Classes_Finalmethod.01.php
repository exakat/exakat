<?php 

class A {
    public function AmethodNonFinal() { $a++;}
    final public function AmethodFinal() { $a++;}
    public final function AmethodFinal2() { $a++;}
}

final class B extends A {
    public function BmethodNonFinal() { $a++;}
    final public function BmethodFinal() { $a++;}
    public final function BmethodFinal2() { $a++;}
}

class C extends A {}

?>
