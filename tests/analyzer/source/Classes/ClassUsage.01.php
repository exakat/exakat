<?php

$x = new Stdclass();
$x2 = new \Stdclass();

$y = new A\B\C; // no ()

$y2 = new \D\R; // absolute Namespace

$y2 = new $a; // variable new
$y2 = new $a[2]; // variable new

?>