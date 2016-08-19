<?php

class x {
    protected static $property = 1, $propertyy = 3;
    protected static $property2 = 2, $property2y = 4;
    protected static $property3, $property3y;
    protected static $property4, $property4y;
    
    function y() {
        $property = self::$property;
        $property3 = self::$property3;

        $propertyy = self::$propertyy;
        $property3y = self::$property3y;
    }

    function y2($property2, $property4, $property2y, $property4y) {
        self::$property2 = $property2;
        self::$property4 = $property4;

        self::$property2y = $property2y;
        self::$property4y = $property4y;
    }

}
?>