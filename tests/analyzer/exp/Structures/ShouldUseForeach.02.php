<?php

$expected     = array('while ($v = array_pop($a)) { /**/ } ',
                      'while ($v = array_shift($a)) { /**/ } ',
                      'do { /**/ } while($v = array_pop($a))',
                      'do { /**/ } while($v = array_shift($a))',
                     );

$expected_not = array('while($v = array_unshift($a)) { /**/ }',
                     );

?>