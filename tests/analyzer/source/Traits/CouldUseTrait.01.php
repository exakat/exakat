<?php

trait t {
    function foo() {}
}

class NoNothing {
    function bar() {}
}

class WithFoo {
    function foo() {}
    function bar() {}
}

class WithFooAndT {
    use t;
    function foo() {}
    function bar() {}
}

class WithT {
    use t;

    function bar() {}
}

?>