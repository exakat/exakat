<?php

class x {
    const anInteger = 1;
    const aReal = 1.2;
    const anArray = [1];
    const aBoolean = true;
    const aString = 'd';
    const anExpression = 1 + 2;
    const aNull = null;
}

$echo[x::anInteger];
$echo[x::aReal];
$echo[x::anArray];
$echo[x::aBoolean];
$echo[x::aString];
$echo[x::anExpression];
$echo[x::aNull];

?>