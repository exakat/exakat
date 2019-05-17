<?php 

foo('localhost', 'root', 'abc');
function foo($a, $b, $c) {
    mysql_connect($a, $b, $c);
}

foo2('localhost', 'root', 343);
function foo2($a2, $b2, $c2) {
    mysql_connect($a2, $b2, $c2);
}

foo3('localhost', 'root', sdd);
function foo3($a3, $b3, $c3) {
    mysql_connect($a3, $c3, $b3);
}

?>