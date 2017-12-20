<?php

function foo() { return $a ?? $b; }

function &foo1() { return $a ?? $b; }

function &foo2() { return $a ? $c : $b; }

function &foo3() { return $a ?: $b; }

?>