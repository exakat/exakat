<?php

$expected     = array('crypt(function ($a) { /**/ } )',
                      'crypt(1)',
                      'crypt(false)',
                      'crypt(true)',
                      'crypt(1.1)',
                      'crypt(-2)',
                      'crypt([13, 23])',
                      'crypt(array(1, 2))',
                      'crypt(array(1, 2, 3))',
                      'strtolower(array(1, 2))',
                      'crypt([14, 24 + $s])',
                     );

$expected_not = array('crypt(<<<\'BC\'

BC)',
                      'crypt(<<<B

B
)',
                      'crypt(__FILE__)',
                      'crypt("D $e f")',
                     );

?>