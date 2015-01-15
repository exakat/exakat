<?php

class useThis {
    static public function useThisButEmpty() {
        $a++;
    }

    static function useThisConstant() {
        useThis::e;
    }


    static function useThisProperty() {
        useThis::$a;
    }

    static function useThisMethod() {
        useThis::b();
    }

    static function bothpropertyandmethod() {
        useThis::$c;
        useThis::d();
    }
}
?>