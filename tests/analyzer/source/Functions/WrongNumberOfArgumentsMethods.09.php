<?php

use x as ax;

const Y = 'x';


class x {
 static function a($b, ...$c) {}
 static function b2($b, Stdclass...$c2) {}
 static function c ($d, $e): x  { $e; }
}

(new x)->a();
(new x)->a(1);
(new x)->a(1,2);
(new x)->c();
(new x)->c(1);
(new x)->c(1,2);

(new ax)->a();
(new ax)->a(1);
(new ax)->a(1,2);
(new ax)->c();
(new ax)->c(1);
(new ax)->c(1,2);

(new y)->a();
(new y)->a(1);
(new y)->a(1,2);
(new y)->c();
(new y)->c(1);
(new y)->c(1,2);

?>