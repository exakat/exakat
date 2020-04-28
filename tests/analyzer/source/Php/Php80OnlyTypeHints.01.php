<?php

function foo() : stringable {}
function foo2(stringable $a) {}

function foo3(\stringable $a) {}

function foo4() : null|x| false {}
function foo5(null|x|false $a) {}

function foo6(x| c $a) : d | e {}


?>