<?php

// KO with native exceptions
new A();
class A extends Exception{}

class B {}
new B();

?>