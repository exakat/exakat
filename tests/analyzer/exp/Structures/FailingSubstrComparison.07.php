<?php

$expected     = array('substr($a, 0, TWO) == \'abc\'',
                      'substr($a, 1, TWO) == \'acc\'',
                      'substr($a, $b, TWO) == "a$b2"',
                      'substr($a, 0, TWO) == "ab" . \'C\'',
                     );

$expected_not = array('\'abc\'',
                      '\'acc\'',
                      '\'ae\'',
                      '\'aec\'',
                      '\'ad\'',
                      '\'ag\'',
                      '\'agc\'',
                      '\'af\'',
                      '\'afc\'',
                      '\'ac\'',
                      '\'ab\'',
                      '\'adc\'',
                     );

?>