<?php

constant("Classe::constante");
constant("constante");
constant("Classe::$constante"); 

// Can't know
constant("$classConstante"); 

$r = new ReflectionClass('Classe');
$id = $r->getConstant($constante);

?>