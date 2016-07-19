<?php

const ONE = 1 ;

class x { const THREE = 3;}

class z {
static public $z = array(
    "Zero" => 2,
    ONE => 1,
    x::THREE => 3,
    4,
    5,
    7,
);

// the array is an inside array, not the first one
static public $z2 = array(
    0 => array(
    "Zero" => 2,
    ONE => 1,
    x::THREE => 3,
    4,
    5,
    8,
    )
);
}

$z = array(
    2,
    ONE => 1,
    x::THREE => 3,
    4,
    5,
    9,
);


print_r($z);
print_r(z::$z);
print_r(z::$z2);

?>