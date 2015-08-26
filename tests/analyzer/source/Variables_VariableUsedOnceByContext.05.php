<?php 

class x {
    function y() {
        static::$links[count(static::$links) - 1][$name] = $value;
        $arrayOnce[1] = 1;
        static::$staticArrayOnce[1] = 1;
        static::$staticArrayAppend[] = 1;
        static::$staticArrayOnceTwoLevel[1][2] = 1;
        static::$staticNoArray = 1;
    }
}

?>