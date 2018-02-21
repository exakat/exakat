<?php

$expected     = array('array(\C . \'D\' => $DD)',
                      'array(C . \'D\' => $DD)',
                     );

$expected_not = array('array(\D . \'D\' => $DD)',
                      'array(D . \'D\' => $DD)',
                      'array(\E . \'D\' => $DD)',
                      'array(E . \'D\' => $DD)',
                     );

?>