<?php

class x {
    static function staticmethod() { print __METHOD__."\n";}
    const constante = 1;
    public static $property = 2;
}

$class = "x";
$class::Staticmethod();
print $class::$property."\n";
constant($class."::constante");
constant("x::constante");
 
?>