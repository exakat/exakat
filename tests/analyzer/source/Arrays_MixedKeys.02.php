<?php

const ONE = 1;

class z {
    const THREE = 3;
    
    function __call($name, $args) {
        print_r($name);
    }
}

$y = new z();
$z = array(
    2 => 1,
    ONE => 2,
    z::THREE => 3,
    4 => 4,
    5 => 5,
    9);


print_r($z);

?>