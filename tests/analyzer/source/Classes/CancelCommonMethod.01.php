<?php

class x {
    function foo0() { doSomething(); }
    function foo1() { doSomething(); }
    function foo2() { doSomething(); }
    function foo3() { doSomething(); }
    function bar() {  }
}

class y1 extends x {
    function foo0() { doSomething(); }
    function foo1() { doSomething(); }
    function foo2() { doSomething(); }
    function foo3() {  }
}

class y2 extends x {
    function foo0() { doSomething(); }
    function foo1() { doSomething(); }
    function foo2() { }
    function foo3() {  }
}

class y3 extends x {
    function foo0() { doSomething(); }
    function foo1() {  }
    function foo2() { }
    function foo3() {  }
}

class y4 extends x {
    function foo0() {  }
    function foo1() {  }
    function foo2() { }
    function foo3() {  }
}

?>