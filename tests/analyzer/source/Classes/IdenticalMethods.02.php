<?php

abstract class a1 {
    abstract function foo1();
}

abstract class a2 extends a1 {
    function foo2() {         ++$a; --$b; ++$c;  }
    function foo2a() {         ++$a; --$b; ++$c; --$d; }
    abstract function foo3();

}

abstract class a3 extends a2 implements i {
    abstract function foo1();
    function foo2() {         ++$a; --$b; ++$c;  }
    function foo2a() {         ++$a; --$b; ++$c; }
    function foo3() {         ++$a; --$b;   }
    function fooi() {}
}

interface i {
    function fooi();
}


?>