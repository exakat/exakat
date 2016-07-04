<?php

const ONE = 1 ;

class x { const THREE = 3;}

class z {
static public $z = [
    "Zero" => 2,
    ONE => 1,
    x::THREE => 3,
    4,
    5,
    7,
];

static public $z2 = [
    0 => [
    "Zero" => 2,
    ONE => 1,
    x::THREE => 3,
    4,
    5,
    8,
    ]
];
}

$z = [
    2,
    ONE => 1,
    x::THREE => 3,
    4,
    5,
    9,
];


print_r($z);
print_r(z::$z);
print_r(z::$z2);

?>