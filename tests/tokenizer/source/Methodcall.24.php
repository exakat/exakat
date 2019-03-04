<?php

class x {
    function foo() {
        return $this;
    }

    function foo2() {
        return $this;
    }

    function foo3() {
        return $this;
    }

    function foo4() {
        return $this;
    }

    function foo5() {
        return $this;
    }
}

$a = new x;
$a->foo()
  ->foo2()
  ->foo3()
  ->foo4()
  ->foo5();
?>