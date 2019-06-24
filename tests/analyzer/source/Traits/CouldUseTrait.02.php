<?php

trait t {
    function foo1() {}
}

trait t2 {
    function foo2() { ++$a;}
}

trait t3 {
    function foo3() { ++$a; if ($a == 1) { --$b;}}
}

trait t4 {
    function foo4() { ++$a;}
}

class WithFoo1 {
    function foo1() {}
}
class WithFoo2 {
    function foo2() { ++$a;}
}
class WithFoo3 {
    function foo3() { ++$a; if ($a == 1) { --$b;}}
}
class WithFoo4 {
    function foo4() { ++$a; if ($a == 1) { --$b;}}
}

?>