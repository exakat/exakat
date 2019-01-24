<?php

foo($a[a]);

function foo(&$a) {
    $a = 1;
}

class x {
    function foo(&$a) {
        $a = 3;
    }

    static function foo2(&$a) {
        $a = 3;
    }
    
    function y() {
        $this->foo($a[2]);
        self::foo2($a[1]);

        self::foo3($a[3]);
        $this->foo4($a[4]);
    }
}

?>