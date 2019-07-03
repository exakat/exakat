<?php

class x {
    function foo() {}
}

class y extends x {
    function foo() {
        parent::foo();
    }

    function foo2() {
        self::foo2();
    }

    function foo4() {
        self::foo3();
    }
}