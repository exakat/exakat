<?php

$expected     = array('$A = $a + 2',
                     );

$expected_not = array('$a = array();',
                      '$b = array();',
                      '$a = [];',
                      '$b = [ ];',
                      '$a = NULL;',
                      '$b = null;',
                      '$a = true;',
                      '$b = true;',
                      '$a = 1;',
                      '$b = 2;',
                      '$B = $a + 2;',
                     );

?>