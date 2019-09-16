<?php

$expected     = array('while ($v = array_pop($a)) { /**/ } ',
                      'while ($v = b($a)) { /**/ } ',
                      'do { /**/ } while($v = array_pop($a))',
                      'do { /**/ } while($v = b($a))',
                     );

$expected_not = array('while($v = array_unshift($a)) { /**/ }',
                     );

?>