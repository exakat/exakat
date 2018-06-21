<?php

function foo1(int $d = 2) { }
function foo2(int $d = 2) { static $a;}
function foo3(int $d = 2) { global $b; }
function foo4(int $d = 2) { global $b; static $d; }
function foo5(int $d = 2) { global $b; static $d; ++$a; }


class y { }

class x extends y {
    function Foo1(int $d = 2) { }
    function Foo2(int $d = 2) { static $a, $b, $c;}
    function Foo3(int $d = 2) { global $b; }
    function Foo4(int $d = 2) { global $b; static $d; }
    function Foo6(int $d = 2) { global $b; static $d, $e, $f; }
    function Foo5(int $d = 2) { global $b; static $d; ++$a; }
}

?>