<?php

class x1 {
    function foo1($a, $b = 1) {}
}
$x1 = new x1;
$x1->foo1(1, 2);

class x2 {
    function foo2($a, $b = 1) {}
}
$x2 = new x2;
$x2->foo2(1, 2);
$x2->foo2(1, 2);

class x3 {
    function foo3($a, $b = 1) {}
}
$x3 = new x3;
$x3->foo3(1, 2);
$x3->foo3(1, 2);
$x3->foo3(1, 2);

class x3a {
    function foo3b($a, $b = 1) {}
}
$x3a = new x3a;
$x3a->foo3b(1, 2);
$x3a->foo3b(1, 2);
$x3a->foo3b(1);

?>
