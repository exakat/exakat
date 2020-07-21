<?php


function toto()
{
    yield 'a' =>
    yield 'b' =>
    yield 'c' =>
    yield 'd' => null;
}

foreach(toto() as $k => $v) {
    var_dump([$k, $v]);
}