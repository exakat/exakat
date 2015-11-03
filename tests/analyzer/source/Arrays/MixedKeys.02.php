<?php

const ONE = 1;
const FIVE = 5;

class z {
    const THREE = 3;
    
    function __call($name, $args) {
        print_r($name);
    }

    static $zInAClass = array(
    'ONE' => ONE,
    2,
    'three' => z::THREE,
    4,
    FIVE => 5,
    6);

}

$five = 'five';

$y = new z();
// This is a method (no warning)
$z = $y->array(
    2,
    ONE,
    z::THREE,
    4,
    5,
    7);

// mixed indexing but in a array (No warning)
$zOutOfAClass = array(
    'ONE' => ONE,
    2,
    'three' => z::THREE,
    4,
    $five => 5,
    8);

?>