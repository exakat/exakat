<?php

trait t {
    function foo() {
        return self::C;
    }
}

interface i { const C = 2;}
trait t2 {
    function foo() {
        return i::C;
    }
}

?>