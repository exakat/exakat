<?php

class Bar {
    public static function foo() {    return 1; }

    public static function foo2() { }

    public static function foo3() {  return 1;}
    public static function foo4() {  return 1;}
    public static function Foo5() {  return 1;}
    public static function foo6() {  return 1;} // unused..
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