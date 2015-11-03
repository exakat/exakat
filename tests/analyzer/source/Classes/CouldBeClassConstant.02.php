<?php

class x {
    static $staticReadWithSelf       = array(1,2,3);
    static $staticReadWithStatic     = array(1,2,4);
    static $staticModifiedWithSelf   = array(1,2,5);
    static $staticModifiedWithStatic = array(1,2,6);
    static $staticModifiedWithThis   = array(1,2,7);
    
    
    function y() {
        $a = self::$staticReadWithSelf;
        $b = static::$staticReadWithStatic;

        $a = self::$staticModifiedWithSelf++;
        static::$staticModifiedWithStatic[] = 1;
        
        $this->staticModifiedWithThis = 1;
    }
}

$z = new x();
$z->y();
print x::$staticModifiedWithThis;

?>