<?php

foreach ($a as &$b) {
    foreach ($b as &$c) {
        $c *= 2;
    }
}
unset($b);

do {
    foreach ($b as &$c2) {
        $c2 *= 2;
    }
} while ($b > 1);

function foo() {
    foreach ($b as &$c3) {
        $c2 *= 2;
    }
}
?>