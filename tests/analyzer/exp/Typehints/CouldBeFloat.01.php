<?php

$expected     = array('$b = 3.1',
                      '$c',
                      '$e',
                      '$f',
                      '$g',
                     );

$expected_not = array('$d',
                      'int $g',
                      'function bari($g);',
                      'function barx($g);',
                     );

?>