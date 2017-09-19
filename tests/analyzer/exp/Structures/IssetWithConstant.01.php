<?php

$expected     = array('isset(X[$a])',
                      'isset(Y::X[$b])',
                     );

$expected_not = array('isset(Y::$x[$b])',
                     );

?>