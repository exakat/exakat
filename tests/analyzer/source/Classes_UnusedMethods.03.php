<?php
function x() {}

array_filter($a, 'x');

array_filter($a, 'Y::x');
array_filter($a, 'X::x');

array_filter($a, array($a, 'x2'));

class Y {
    static function x() {}
    static function x2() {}
    static function x3() {}
    
    function z() {
        array_filter($a, array($this, 'x'));
    }
}

$result = array_diff_uassoc($array1, $array2,$array3, $array4,'Y2::x2');
$result = array_diff_uassoc($array1, $array2,$array3, $array4,'X2::x2');

class Y2 {
    static function x2() {}
}
