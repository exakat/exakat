<?php

class x {
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

}

?>