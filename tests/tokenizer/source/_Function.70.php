<?php

$a = function &() {};
$b = function () {};
$a = function &($a=3) {};
$b = function ($b = 2) {};

function &b1() {}
function b2() {}
function &b3($a=3) {}
function b4($b = 2) {}

?>