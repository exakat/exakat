<?php

class x {

static $x = 2;
const y = 2;

static function &ar() {
    return strtolower(self::y);
    return @self::$x;
    return array(1);
    return __DIR__;
    return 1;
    return true;
    return ($y);

    return $x;
    return $this->$x;
    return x::$x;
    return $x[3];
    
    
}
}
x::ar();

?>