<?php

const C = 1;

class C { static function method() { echo __METHOD__."\n";}}

echo C;

C::method();

?>