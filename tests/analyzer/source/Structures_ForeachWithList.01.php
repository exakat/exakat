<?php

$array = [
    [10, 20],
    [30, 40],
];
foreach ($array as list($a1, $b1)) {
    echo "First: $a; Second: $b\n";
}

foreach ($array as $id2 => list($a2, $b2)) {
    echo "First: $a; Second: $b\n";
}

foreach ($array as $id3 => $a3) {
    echo "First: $a; Second: $b\n";
}

foreach ($array as $a4) {
    echo "First: $a; Second: $b\n";
}

/*
$a = array(1,2,3);
$x = array($a => 1, 2, "C");

print_r($x);

foreach($x as $i => $j) {
    print_r($i);
}
*/
?>