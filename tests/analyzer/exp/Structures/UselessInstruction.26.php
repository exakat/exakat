<?php

$expected     = array('bar($b)',
                     );

$expected_not = array('strtolower(\'a\')',
                      'sort($a)',
                      'array_shift($a)',
                      'foo($a)',
                     );

?>