<?php

$array = [
    [10, 20],
    [30, 40],
];
foreach ($array as list($a1, $b2)) {
    echo "First: $a1; Second: $b2\n";
}

foreach ($array as $id => list($a3, $b4)) {
    echo "First: $a3; Second: $b4\n";
}

?>