<?php

$expected     = array('substr($a, 0, 2) == \'abc\'',
                      'substr($a, 1, 2) == \'acc\'',
                      'substr($a, 0, 2) == "ab" . \'C\'',
                      'substr($a, $b, 2) == "a$b2"',
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