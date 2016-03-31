<?php

class A0  { public function __construct( ) {} }
class A1 { public function __construct($a) {} }

class B0 {public function __construct( ) {}}
class BB0 extends B0 {}
class B1 {public function __construct($a ) {}}
class BB1 extends B1 {}

class C0 {public function __construct( ) {}}
class CC0 extends C0 {}
class CCC0 extends CC0 {}
class C1 {public function __construct($a ) {}}
class CC1 extends C1 {}
class CCC1 extends CC1 {}

new A0();
new A1();

new BB0();
new BB1();

new CCC0();
new CCC1();

?>