<?php

function x() {}

array_filter($a, 'x');

array_filter($a, array('Y', 'parent::x'));
array_filter($a, array('NoSuchClass', 'parent::x'));
array_filter($a, array('NoParent', 'parent::x'));

array_filter($a, array($a, 'x2'));

class Y extends Yparent {
    static function x2() {}

    function xy() { }
    
    function z() {
        array_filter($a, array($this, 'xy'));
    }
}

class NoParent {
    function x() { $a++;}
}

class Yparent {
    static function x() {}
}

//array_filter($a, array('Y2', 'parent::x3'));

$result = array_diff_uassoc($array1, $array2,$array3, $array4, array('Y2', 'parent::x3'));
$result = array_diff_uassoc($array1, $array2,$array3, $array4, array('X2', 'parent::x3'));
$result = array_diff_uassoc($array1, $array2,$array3, $array4, array('Z2', 'parent::x3'));
$result = array_diff_uassoc($array1, $array2,$array3, $array4, array('Y2', 'x3'));

array_walk($array, array('X2', 'parent::x3'));
array_walk($array, array('Y2', 'parent::x3'));
array_walk($array, array('Z2', 'parent::x3'));

class Y2 extends Y2parent {
    static function x2() {}
}

class Y2parent {
    static function x3() {}
}

class Z2 extends Z2parent {
    static function x2() {}
}

class Z2parent {
    static function x3() {}
}

strtolower(array('Y2', 'parent2::x2'));

?>