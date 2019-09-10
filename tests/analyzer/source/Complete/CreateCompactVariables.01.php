<?php

function foo() {
    $a = 1;
    $b = 2;
    $c = "a";
    compact('a', 'b');
    compact($c);
}
?>