<?php

function foo(a|b|c|d $a) {
    $a->bar();
}

function goo(b|c|null $b) {
    $b->bar();
}

class a {
    function bar($a) {}
}
class b {
    function bar() {}
}
class c {
    function bar() {}
}

?>