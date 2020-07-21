<?php


function toto()
{
    yield 'a' =>
    yield 'b' =>
    yield 'c' =>
    yield 'd' => yield from array(1,2,3);
}

foreach(toto() as $k => $v) {
    var_dump([$k, $v]);
}