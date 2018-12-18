<?php
array_map('foo', $a);
array_map('x::foo', $a);
array_map(array('\\x', 'foo'), $a);
function foo() {
    return true;
}

array_map('foo2', $a);
function foo2() {
    return rand(3, 4) + 5;
}

class x {
    static function foo() {
        return 2 >> 9;
    }
}
?>