<?php

$expected     = array('$b->AD4 = \'D\' ** $b->AD4');

$expected_not = array('P::$c = C(\'D\', P::$c)', 
                      '$b->AD = C(\'D\', $b->AD)', 
                      '$b[\'B\'] = C(\'D\', $b[\'B\'])',
                      'P::$c2 = C(\'D\', P::$c3)', 
                      '$b->AD2 = C(\'D\', $b->AD4)', 
                      '$b[\'B2\'] = C(\'D\', $b[\'B\'])'
);

?>