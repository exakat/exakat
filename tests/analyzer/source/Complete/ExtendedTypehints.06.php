<?php

function foo(B $a)  {}

class B implements A {}
class C2 extends B {}
class D2 implements A2 {}

interface A {}
interface A2 extends A {}

class D implements E {}

interface C {}

?>