<?php

($a1 = new x)->p;
($a2 = new x)->m;

($a3 = new x)::$p;

$a->b($a4 = new x);
for(($a5 = new x); $a->b() < 10; ++$i) {}
for($a6 = new x; $a->b() < 10; ++$i) {}

?>