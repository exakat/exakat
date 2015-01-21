<?php

namespace x\y {
    use x\y as z;

class a {
    function normala() {
        echo __METHOD__."\n";
    }
}

class b extends a {
    function normalb() {
        echo __METHOD__."\n";
    }
}

class c extends b {
    function normalc() {
        parent::normalb();
        a::normala();
        z\a::normala();
        d::normald();
        e::normale();
        c::normale();
    }
}

class d extends c {
    function normald() {
        echo __METHOD__."\n";
    }
}

class e {
    function normale() {
        echo __METHOD__."\n";
    }
}

$c = new c();
$c->normalc();
}
?>