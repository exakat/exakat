<?php

class x {
 static function a($b, ...$c) {}
 static function b2($b, Stdclass...$c2) {}
 static function c ($d, $e): x  { $e; }
}

X::a();
X::a(1);
X::a(1,2);
X::c();
X::c(1);
X::c(1,2);

?>