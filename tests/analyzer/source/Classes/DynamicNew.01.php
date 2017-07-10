<?php

$x = new $classe();
$x2 = new $classe[1];
$x3 = new $object->property;
//$x4 = new Stdclass::constante; This won't compile
$x5 = new Classe::$staticproperty;
new class {};
new stdClass();

?>