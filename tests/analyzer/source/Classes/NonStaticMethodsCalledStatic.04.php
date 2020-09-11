<?php

namespace x\y {
    use x\y as z;

class a {
    function normala() {
        echo __METHOD__."\n";
    }

    static function statica() {
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
        z\a::statica();
        d::normald();    // d is not in the above class tree
        d::normala();    // d is not in the above class tree, normala doesn't exist in d
        d::statica();
        e::normale();    // e is not in the current class tree,
        c::normale();
        c::normala();
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