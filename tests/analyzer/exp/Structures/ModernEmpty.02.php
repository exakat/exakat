<?php

$expected     = array('$forEmpty2 = preg_replace(\'/ASD/\', \'\', $x[0])',
                      '$forEmpty = preg_replace(\'/ASD/\', \'\', $x[0])',
                     );

$expected_not = array('$forEmpty = preg_replace(\'/ASD/\', \'\', $x[0])',
                     );

?>