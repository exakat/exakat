<?php

abstract class x {
    const A = 4;
    
    abstract function move ( $a, 
                             $b = self::B,
                             $c = self::C,
                             $d = parent::D ) ;
}

echo self::C;

?>