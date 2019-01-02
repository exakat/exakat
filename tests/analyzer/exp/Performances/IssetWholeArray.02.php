<?php

$expected     = array('isset($c->d[7][8], $c->d[7])', 
                      'isset($c->d[9], $c->d[9][8])', 
                      'isset($c->d, $c->d[3])', 
                      'isset($c->d[6], $c->d[5])', 
                      'isset($c->d[4], $c->d)', 
                      'isset($c->d[2][3]) || isset($c->d[2][3][1])', 
                      'isset($c->d[2]) || isset($c->d[2][1])',
                     );

$expected_not = array('isset($c->d) || isset($c->b[1])',
                     );

?>