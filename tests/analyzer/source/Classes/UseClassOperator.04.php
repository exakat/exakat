<?php

class X extends B {
    function foo() {
        $a = '\X';
        $b = '\x';
        $c = '\a';
        $d = 'x';
        $d = "x$a";
        $e = 'static';
        $f = 'PARENT';
        $g = 'Self';
    }
}


?>