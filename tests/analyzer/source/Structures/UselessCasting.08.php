<?php

function foo() : array|string {}

$b = (array) foo();
$c = (string) foo();
$d = (int)  foo();

function goo()  {}

$b = (array) goo();
$c = (string) goo();

$b = (array) hoo();

function joo() : bool {}
$d = (bool)  joo();

?>