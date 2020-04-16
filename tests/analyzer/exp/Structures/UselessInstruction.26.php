<?php

$expected     = array(
                     );

$expected_not = array('strtolower(\'a\')',
                      'sort($a)',
                      'array_shift($a)',
                      'foo($a)',
                      // functioncall have been removed until we can spot functions with side effects
                      'bar($b)',
                     );

?>