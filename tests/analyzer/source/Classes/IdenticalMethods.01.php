<?php

class a1 {
    function foo1() {         ++$a; --$b;  }
}

class a2 extends a1 {
    function foo2() {         ++$a; --$b; ++$c;  }
    function foo2a() {         ++$a; --$b; ++$c; --$d; }

}

class a3 extends a2 {
    function foo1() {         ++$a; --$b;  }
    function foo2() {         ++$a; --$b; ++$c;  }
    function foo2a() {         ++$a; --$b; ++$c; }
    function foo3() {         ++$a; --$b;   }

}

?>