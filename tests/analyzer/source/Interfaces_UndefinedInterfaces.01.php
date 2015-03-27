<?php

interface MyInterface{}

$a instanceof Stdclass;
$b instanceof MyInterface;
$c instanceof \MyInterface;
$d instanceof undefinedInterfacei;
$e instanceof \undefinedInterfacei;

interface MyInterface{}

function x(Stdclass $a, 
           MyInterface $b,
           \MyInterface $c,
           undefinedInterfacet $d,
           \undefinedInterfacet $e) {}

?>