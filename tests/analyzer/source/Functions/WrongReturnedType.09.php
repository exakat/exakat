<?php

class x {
    function foo() {}
    function foo2() : string {}

    public function foo3(): array {
        return $a->b();
    }

    public function foo4(): array {
        return $a->b();
        return $this->foo3();
        return $this->foo2();
    }
}

function bar(x $x) {
    return $x->foo();
}

function bar2(x $x) {
    return $x->foo2();
}

?>