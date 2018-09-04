<?php

$expected     = array('Phar( )',
                      '\Phar( )',
                      'Phar', // constant
                      '\Phar', // method
                      '\Phar',
                     );

$expected_not = array('\A\phar',
                     );

?>