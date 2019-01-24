<?php

foo($a->a);

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
        $this->foo($a->b);
        self::foo2($a->c);

        self::foo3($a->d);
        $this->foo4($a->e);
    }
}

?>