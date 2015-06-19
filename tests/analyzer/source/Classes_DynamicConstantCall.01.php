<?php

constant("Classe::constante");
//constant("Classe::$constante"); not yet

$r = new ReflectionClass('Classe');
$id = $r->getConstant($constante);

?>