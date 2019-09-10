<?php

$a             = array();   // level 1;
$a[1]          = array();   // level 2
$a[1][2]       = array();   // level 3 : still valid by default
$a[1][2][3]    = array();   // level 4 
$a[1][2][3][4] = 1;   // level 4 

$a[1][][3][4] = array();   // level 4 

$a[1][][3] = range(1,2);   // level 4 
$a[1][][3] = count(array(1,2,3));   // level 4 

$a[1][][3] = foo(1,2);   // level 4 
function foo() : array {}
$a[1][][3] = foo2(1,2);   // level 4 
function foo2() : int {}


?>