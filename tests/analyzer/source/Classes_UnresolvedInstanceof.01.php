<?php

class a extends Exception {}

$a instanceof a;
$a instanceof \a;
$a instanceof notDefinedClass;

?>