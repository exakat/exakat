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
    function foo2() { ++$a;}
}

class WithStaticFoo1 {
    static function foo1() {}
}

class WithStaticFoo2 {
    static function foo2() { ++$a;}
}

?>