<?php

$expected     = array('eval(\'$x = 1;\')',
                     );

$expected_not = array('eval(\'$x = 2;\')',
                      'eval(\'$ev = 3\')',
                      'eval(\'$ev = 4\')',
                     );

?>