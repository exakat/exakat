<?php

A:

$a++;

goto A;

B:
$c++;

goto B;
goto A;

$c = $d ? E : F;

?>