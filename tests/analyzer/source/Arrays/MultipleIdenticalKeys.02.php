<?php

class x {
    const A = 1;
    const B = 2;
    const C = 3;
    const D = 4;
    
static $a = array(
        self::A => '1',
        self::B => '2',
        self::C => '3',
        self::D => '4',
);

static $b = array(
        self::A => '1',
        self::B => '2',
        self::B => '3',
        self::D => '4',
        self::D => '4',
        self::D => '4',
        self::D => '4',
);

static $c = array(
        self::A => '1',
        self::B => '2',
        self::B => '3',
        self::D => '4',
        x::D => '4',
        \x::D => '4',
);

}

?>