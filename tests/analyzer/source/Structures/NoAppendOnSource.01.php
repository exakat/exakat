<?php

foreach($a as &$b) {
    $a[] = 1;
}

foreach($A as $B) {
    $a[] = 1;
}

foreach($a->b as &$b) {
    $a->b[] = 1;
}

foreach($a::$b as &$b) {
    $a::$b['3'][] = 1;
}

foreach(foo() as &$b) {
    $x[] = 1;
}

?>