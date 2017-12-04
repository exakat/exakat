<?php

$expected     = array('isset(X)',
                      'isset(Y::X)',
                     );

$expected_not = array('isset(Y::$x[$b])',
                     );

?>