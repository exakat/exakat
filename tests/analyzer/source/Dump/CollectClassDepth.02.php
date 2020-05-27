<?php

class a1 {}

class a2 extends a1 {}
class b2 extends a1 {}

class a3 extends a2 {}

class a4 extends a3 {}

$a = new class () extends a4 {};

class c5 extends c4 {}
?>