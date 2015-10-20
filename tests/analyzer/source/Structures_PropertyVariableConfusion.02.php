<?php

class x {
    protected static $property = 1;
    protected static $property2 = 2;
    protected static $property3;
    protected static $property4;
    
    function y() {
        $property = self::$property;
        $property3 = self::$property3;
    }

    function y2($property2, $property4) {
        self::$property2 = $property2;
        self::$property4 = $property4;
    }

}
?>