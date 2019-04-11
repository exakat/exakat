<?php

$z = $_POST;
foo($_GET, $_post, $z);
foo($_GET, 1, 2);
foo(1, $_post, 3);

$y = new x;
$y->foo2(1, $z);
$y->foo2($z, 2);

x::foo3(3, $z);
x::foo3($z, 3);

function foo($a, $b, $c) {
    shell_exec($a);
    shell_exec($b);
    shell_exec($c);
    
}

class x {
    function foo2($b, $c) {
        shell_exec($b);
    }

    static function foo3($b, $c) {
        shell_exec($b);
    }

    function bar() {
        $e = $_GET['dd'];
        shell_exec($e);
    }
}

?>