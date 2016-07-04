<?php

// KO with native exceptions
throw RuntimeException();
throw \RuntimeException();

throw A();
class A extends Exception{}

// OK, it is a function
throw B();
function B(){}

// OK, because it's a function too.
throw C();
function C(){}
class C extends Exception{}

// KO, as D is not an exception
throw D();
class D {}

?>