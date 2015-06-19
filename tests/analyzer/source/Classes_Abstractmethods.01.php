<?php 

abstract class A {
    public function AmethodNonAbstract() { $a++;}
    abstract public function AmethodAbstract() ;
    public abstract function AmethodAbstract2() ;
}

abstract class B extends A {
    public function BmethodNonAbstract() { $a++;}
    abstract public function BmethodAbstract() ;
    public abstract function BmethodAbstract2() ;
}

abstract class C extends A {}

?>
