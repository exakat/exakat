<?php

class c2 {}

interface i2 {}

trait t2 { }

class c3 {
    use t, t2, t3;
}

function f2() {}

f();
f2();
f3();
A\fa();
B\fb();