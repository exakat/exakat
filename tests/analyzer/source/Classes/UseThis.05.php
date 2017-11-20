<?php

class B {
    public static function C1() {
        return new self('d');
    }
}

class A extends \B {
    public static function C1() {
        return new parent('d');
    }
}


class C extends \A {
    public static function C1() {
        return new D('d');
    }
}


