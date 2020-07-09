<?php


$de = function (string $a) {};
foo($de);

function foo($d) {

$d('a', 'b');
$d(true,3);
$d(3, 4);
$d([], "c");
}

?>