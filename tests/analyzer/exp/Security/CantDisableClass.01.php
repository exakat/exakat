<?php

$expected     = array('Phar( )',
                      '\Phar( )',
                      'Phar', // constant
                      '\Phar', // method
                     );

$expected_not = array('\A\phar',
                     );

?>