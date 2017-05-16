<?php

class Bar {
    public function foo() {    return 1; }

    public function foo2() { }

    public function foo3() {  return 1;}
    public function foo4() {  return 1;}
    public function Foo5() {  return 1;}
    public function foo6() {  return 1;} // unused..
}

Bar::foo();
Bar::foo();
Bar::foo();
Bar::foo();
Bar::foo();
Bar::foo();

Bar::foo3() + 1; // Used,
$a = Bar::foo4(); // Used,
Bar::foo(Bar::foo5()); // Used,

?>