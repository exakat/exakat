<?php

namespace A {
const anInteger = 1;
const aReal = 1.2;
const anArray = [1];
const aBoolean = true;
const aString = 'd';
const anExpression = 1 + 2;
const aNull = null;
}

namespace B {

$echo[\A\anInteger];
$echo[\A\aReal];
$echo[\A\anArray];
$echo[\A\aBoolean];
$echo[\A\aString];
$echo[\A\anExpression];
$echo[\A\aNull];
}
?>