<?php

interface MyInterface{}
interface MyInterfacet{}

$a instanceof Stdclass;
$b instanceof MyInterface;
$c instanceof \MyInterface;
$d instanceof undefinedInterfacei;
$e instanceof \undefinedInterfacei;

function x(Stdclass $a, 
           MyInterfacet $b,
           \MyInterfacet $c,
           undefinedInterfacet $d,
           \undefinedInterfacet $e) {}

?>