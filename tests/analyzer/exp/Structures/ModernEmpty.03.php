<?php

$expected     = array('$for->Empty2 = preg_replace(\'/ASD/\', \'\', $x[0])',
                      );

$expected_not = array('$for->Empty = preg_replace(\'/ASD/\', \'\', $x[0])',
                      );

?>