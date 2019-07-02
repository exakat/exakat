<?php

$expected     = array('Phar( )',
                      '\\Phar( )',
                      'Phar',
                      '\\Phar',
                      'PhaR',
                      '\\PhAR',
                      'C(3)',
                      'C',
                     );

$expected_not = array('\\A\\phar',
                     );

?>