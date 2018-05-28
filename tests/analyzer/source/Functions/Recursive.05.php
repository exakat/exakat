<?php
class x {
    function nonRecursive($x) {
        $y = new Stdclass();
        $y->nonRecursive();
    }
    
    function recursive($x) {
        $y = $this->recursive();
    }

    function recursive2($x) {
        $y = $this->RECURSIVE2();
    }

    static function recursive3a($x) {
        $y = self::RECURSIVE3a($x);
    }

    function recursive3b($x) {
        $y = self::RECURSIVE3b($x);
    }

    static function recursive4($x) {
        $y = self::recursive4($x);
    }

    static function recursive5($x) {
        $y = static::recursive5($x);
    }

    static function recursive6($x) {
        $y = X::recursive6($x);
    }
    
    function nonRecursive2($x) {
        StdClass::nonRecursive2();
    }
}
?>