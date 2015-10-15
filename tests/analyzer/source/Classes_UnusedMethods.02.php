<?php
function x() {}

array_filter($a, 'x');

array_filter($a, array('Y', 'x'));
array_filter($a, array('X', 'x'));

array_filter($a, array($a, 'x2'));

class Y {
    static function x() {}
    static function x2() {}
    static function x3() {}
    
    function z() {
        array_filter($a, array($this, 'x'));
    }
}

$result = array_diff_uassoc($array1, $array2,$array3, $array4,array('Y2', 'x2'));
$result = array_diff_uassoc($array1, $array2,$array3, $array4,array('X2', 'x2'));

class Y2 {
     function x2() {}
     function x22() {}
}

?>