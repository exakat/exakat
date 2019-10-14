<?php

function foo() : string {}

$b = (string) foo();
$c = (bool) foo();
$d = (int)  foo();

(array) (new x)->bar();
(string) (new x)->bar();

class x {
    function bar() : array { }
}
?>