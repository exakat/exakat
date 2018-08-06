<?php

$expected     = array('is_real($i)', 
                      '(real) $i', 
                      '(REAL) $i',
                     );

$expected_not = array('(float) $i',
                      '$a->is_real( )',
                     );

?>