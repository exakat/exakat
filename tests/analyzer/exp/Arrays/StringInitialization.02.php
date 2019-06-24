<?php

$expected     = array('$this->b',
                      '$this->d',
                      '$a',
                      '$f',
                     );

$expected_not = array('$c',
                      '$b = \'\' . "b"', 
                      '$this->d = \'2\'',
                      '$f = \'\'',
                      '$a = \'\'',
                      '$f', 
                      '$d', 
                     );

?>