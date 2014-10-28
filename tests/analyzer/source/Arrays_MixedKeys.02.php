<?php

const ONE = 1;

class z {
    const THREE = 3;
    
    function __call($name, $args) {
        print_r($name);
    }
}

$y = new z();
$z = $y->array(
    2,
    ONE,
    z::THREE,
    4,
    5,
    9);


print_r($z);

?>