<?php

class y {
    const ONE = 1;
    const THREE = 3;

    function x( $one = ONE, $oneMultiplyTwo = ONE * 2, $three = 2 + 1, $oneThird = ONE / self::THREE, 
         $sentence = 'The value of THREE is '.self::THREE) {
         $x++;
    }
}
?>