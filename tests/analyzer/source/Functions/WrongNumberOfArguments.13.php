<?php

function bar() {
    $a = function ($c) {};
    $a();
    $a(1);
    $a(2,3);

    $b = fn ($d) => $d;
    $b();
    $b(1);
    $b(2,3);

}
