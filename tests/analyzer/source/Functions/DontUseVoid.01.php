<?php

function fooVoid() : void {}

function fooInt() : int {}

function fooNone() {}

$a = fooVoid();
fooInt(fooVoid(1));


$a = fooNone();
fooInt(fooNone());

$a = fooInt();
fooInt(fooInt());
?>