<?php

function foo(...$a) {}

foo(1);
foo([1]);
foo(1,2,3);

function goo(array $a) {}

goo(1);
goo([1]);
goo(1,2,3);

function hoo($a) {}

hoo(1);
hoo([1]);
hoo(1,2,3);

?>