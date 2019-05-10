<?php
$a = function(&$b, $c) { $b++; };

foreach ($val as &$a) {
    $a($a, 0);
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