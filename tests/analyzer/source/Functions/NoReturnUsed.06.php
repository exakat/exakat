<?php

class x {
    function foo() {
        return 1;
    }

    function bar() {
        return 1;
    }
}

function bar2(x $a) {
    $a->foo();
    $c = $a->bar();
}

(new x)->foo();
$c = (new x)->bar();

function bar3() : x { }

bar3()->foo();
bar3()->bar();


?>