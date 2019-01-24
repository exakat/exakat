<?php

foo($aa);

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
        $this->foo($ab);
        self::foo2($ac);

        self::foo3($ad);
        $this->foo4($ae);
    }
}

?>