<?php
class x {
    public static $usedByX = 1;
    public static $usedByXFQN = 1;
    public static $unused = 2;
    public static $usedInside = 3;
    public static $usedButWrongClass = 4;
    
    function y() {
        self::$usedInside = 3;
    }
}

x::$usedByX = 4;
\x::$usedByXFQN = 4;
$x->usedButStatic = 3;
y::$usedButWrongClass = 3;

?>