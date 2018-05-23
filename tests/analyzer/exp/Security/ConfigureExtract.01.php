<?php

$expected     = array('extract($array)',
                     );

$expected_not = array('extract($array, EXTR_OVERWRITE)', 
                      'extract($array, EXTR_PREFIX_ALL, \'php_\')', 
                      'extract($array, x, 3)', 
                      'extract($array, EXTR_OVERWRITE)', 
                      'extract($array, EXTR_SKIP)', 
                      'extract($array, x, 3, 4);', 
                      'classe::extract($array)', 
                     );

?>