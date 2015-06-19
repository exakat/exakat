<?php

namespace a\b\c\d;

$x = new \Exception();
$x = new Exception();
$y = new UndefinedClass();
$z = new DefinedClass();

class DefinedClass {}

?>
