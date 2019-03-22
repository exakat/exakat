<?php

class c2 {}

interface i2 {}

trait t2 { }

class c3 {
    use t, t2, t3;
}

$a instanceof i;
$a instanceof i2;

new c;
new c2;
