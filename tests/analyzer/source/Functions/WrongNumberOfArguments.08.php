<?php

class a {
    function x($a, $b = 1) { }
}
$a = new a;

$a->x();
$a->x(1);
$a->x(2, 3);
$a->x(4, 5, 6);
$a->x(7, 8, 9, 10);

?>