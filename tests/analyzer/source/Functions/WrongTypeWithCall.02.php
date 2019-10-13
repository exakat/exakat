<?php

foo(1);
foo(array());
function foo($array) {
    return array_fill($array, 2, 'd');
}

const C = 2.2;
define('D', 3.3);

foo2(1);
foo2("1");
foo2($a.$b);
foo2($a + $b);

foo2(C);
foo2(\D);

foo2(array(1));

function foo2(string $string) {
    return strtolower($string, 23, 'd3   ');
}

?>