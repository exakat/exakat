<?php

function bar(&$a) { }

class foo {
    static function bar(&$a, $b) { }

    static function bar2($a, $b) { }
}
?>