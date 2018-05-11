<?php

$expected     = array('substr($a, 0, TWO) == \'abc\'',
                      'substr($a, 1, TWO) == \'acc\'',
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