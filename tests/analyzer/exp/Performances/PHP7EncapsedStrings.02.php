<?php

$expected     = array('$b[2][3] . \'de\'', 
                      'f(3)[3] . \'dr\'', 
                      '$b . \'c\'', 
                      '$b->e . \'d\'', 
                      '$b->e->f . \'d\'',
                     );

$expected_not = array('__DIR__ . \'c\'',
                      'A_CONSTANT . \'d\'',
                     );

?>