<?php

class x1 extends Exception {}
class x2 extends Exception {}
class x3 extends Exception {}
class x4 extends Exception {}
class x5 extends Exception {}
class x6 extends Exception {}
class x7 extends Exception {}
class x8 extends Exception {}
class x9 extends Exception {}
class x10 extends Exception {}

throw new x1();
throw new X2;

throw $y;

throw $x = new x3();
throw ($x = new \X4());

$w = new x5;
throw $w;

new X6('a');

$a->throw($b);


?>  