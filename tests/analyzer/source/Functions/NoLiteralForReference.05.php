<?php

foo();
foo((1));
foo(((2)));
foo($a = 1 + 3);
foo(($a = 1 + 4));
foo($a);

function foo(&$r) { }
?>