<?php
$z = function(&$b, $c) { $b++; };

foreach ($val as &$a) {
    $z($a, 0);
}

$z2 = function($b, $c) { $b++; };

foreach ($val as &$a2) {
    $z2($a2, 0);
}

foreach ($val as &$b) {
    $a(0, $b);
}

function foo(&$b, $c) { $b++; }

foreach ($val as &$d) {
    foo($d, 1);
}

foreach ($val as &$e) {
    foo($f, $e);
}

foreach ($val as &$g) {
    parse_str($g, $e);
}

foreach ($val as &$h) {
    parse_str($g, $h);
}

?>