<?php

function x() {}

array_filter($a, 'x');

array_filter($a, array('Y', "parent::$x"));
array_filter($a, array('NoSuchClass', "parent::$x"));
array_filter($a, array('NoParent', "parent::$x"));

array_filter($a, array($a, "a$x2"));

class Y extends Yparent {
    static function x2() {}
    
    function z() {
        // wrong (concatenation)
        array_filter($a, array($this, "$x"));
        array_filter($a, array($this, "parent::$x4"));

        // OK (strings)
        array_filter($a, array($this, 'x33'));
        preg_replace_callback('asd', array($this, 'x34'), 'df');
    }
}

class NoParent {
    function x() { $a++;}
}

class Yparent {
    static function x() {}
}

$result = array_diff_uassoc($array1, $array2,$array3, $array4, array('Y2', "parent::$x4"));
$result = array_diff_uassoc($array1, $array2,$array3, $array4, array('X2', "parent::$x4"));
$result = array_diff_uassoc($array1, $array2,$array3, $array4, array('Z2', "parent::$x4"));
$result = array_diff_uassoc($array1, $array2,$array3, $array4, array('Y2', 'x3'));

array_walk($array, array('X2', "parent::$x4"));
array_walk($array, array('Y2', "parent::$x4"));
array_walk($array, array('Z2', "parent::$x4"));

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