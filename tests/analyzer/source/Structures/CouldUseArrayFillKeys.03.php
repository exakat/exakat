<?php
foreach($a as $b => $c ) {
    $a[$b] = 3;
}

foreach($a2 as $b => $c ) {
    $a2[$b] = 3 + 4 * 5;
}

foreach($A1 as $b => $c ) {
    $A1[$b2] = 3 + 4 * 5;
}

foreach($a1 as &$c ) {
    $c = 3 + 4 * 5;
}

?>