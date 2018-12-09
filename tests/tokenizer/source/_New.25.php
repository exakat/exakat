<?php

class y {
    static function f ($x) {
        print __METHOD__;
    }
}

class x extends y {
    function foo() {
        new self;
        new parent;
        new static;
        new static();
        new self();
        new parent();
    }
}
