<?php

use x as y;

class x {
    function bar2() {
        print __METHOD__."\n";
        var_dump($this);
    }
}

class x2 extends x {
    function foo() {
        parent::bar();
        parent::bar();
    }
    
    function foofoo() {
        PARENT::bar();
    }

    function bar() {
        $a = 2;
    }
}

$a = new x2();
$a->foo();
x::bar();

?>