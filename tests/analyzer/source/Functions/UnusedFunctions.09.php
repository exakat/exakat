<?php

namespace {
    function foo() {}
    function foo2() {}
    function foo3() {}
    function foo4() {}
}

namespace A\B\C {

class d extends c {
    protected function b() {
            foo($this->finalName);
    }
}

foo2();

array_map('foo4', range(0, 4));

}
