<?php

class x extends y {
    const A = 4;
    
    function move ( $a, 
                    $b = self::B,
                    $c = self::C,
                    $e = parent::D ) {}
}

class y {}

new class { function move($d = self::D) {}};

echo self::E;

?>