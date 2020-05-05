<?php

trait t {
    function foo1() {}
}

trait t2 {
    static function foo2() { }
}

class WithFoo1 {
    function foo1() {}
}

class WithFoo2 {
    function foo2() { }
}

class WithFinalFoo1 {
    final function foo1() {}
}

class WithFinalFoo2 {
    final static function foo2() { ++$a;}
}

?>