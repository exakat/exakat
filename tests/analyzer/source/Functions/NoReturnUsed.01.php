<?php

function foo() {
    return 1;
}

function foo2() { }

function foo3() {  return 1;}
function foo4() {  return 1;}
function foo5() {  return 1;}
function foo6() {  return 1;} // unused..

foo();
foo();
foo();
foo();
foo();
foo();

foo3() + 1; // Used,
$a = foo4(); // Used,
foo(foo5()); // Used,

?>