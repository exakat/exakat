<?php

class x {
    function foo($a, $b = 1, $c = 2) {}
}
$x = new x();

$x->foo(1,2);
$x->foo(1,2);
$x->foo(1,2);
$x->foo(1,2);
$x->foo(1,2);

class y {
    function goo($a, $b = 1, $c = 2) {}
}

(new y)->goo(1,2);
(new y)->goo(1,2);
(new y)->goo(1,2);
(new y)->goo(1,2);
(new y)->goo(1,2, 3);

?>