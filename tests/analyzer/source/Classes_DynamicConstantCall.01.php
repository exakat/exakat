<?php

constant("Classe::$constante");

$r = new ReflectionClass('Classe');
$id = $r->getConstant($constante);

?>