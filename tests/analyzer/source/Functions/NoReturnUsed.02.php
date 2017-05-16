<?php

class Bar {
    function foo() {    return 1; }
    
    function foo2() { }
    
    function foo3() {  return 1;}
    function foo4() {  return 1;}
    function Foo5() {  return 1;}
    function foo6() {  return 1;} // unused..
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