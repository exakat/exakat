<?php

foo(1);
foo(array());
function foo($array) {
    return array_fill($array, 2, 'd');
}

foo2(1);
foo2(array(1));
function foo2(array $array) {
    return array_fill($array, 23, 'd3   ');
}

?>