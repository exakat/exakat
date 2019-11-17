<?php

$expected     = array('\'a\' . $c++', 
                      '++$i % $element->columns', 
                      '$i++ * 20',
                     );

$expected_not = array('--$j',
                      '++$iu',
                     );

?>