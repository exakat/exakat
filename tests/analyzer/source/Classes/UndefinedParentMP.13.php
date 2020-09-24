<?php

use a\b\y as y;


class x2 extends Stubs\stub {
    function foo() {
        parent::m2();
    }
}

class x extends y {
    function foo1() {
        parent::m();
    }
}

class x3 extends unknown {
    function foo() {
        parent::m3();
    }
}

?>