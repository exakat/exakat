<?php

class y {
    static function f ($x) {
        print __METHOD__;
    }
}

class x extends y {
    function foo() {
        array_map(array('self', 'f'), $r);
        array_map(array('static', 'f'), $r);
        array_map(array('parent', 'f'), $r);
    }
}

array_map(array('self', 'f'), $r);
array_map(array('static', 'f'), $r);
array_map(array('parent', 'f'), $r);
