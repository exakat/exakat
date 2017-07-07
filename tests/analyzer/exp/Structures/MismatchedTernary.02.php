<?php

$expected     = array('$b ? 1 : \'c\' . \'d\'', 
                      '$b ? 1.0 : 3 + 3');

$expected_not = array('$b ? "c" : \'c\' . \'d\';');

?>