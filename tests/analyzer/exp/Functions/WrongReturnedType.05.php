<?php

$expected     = array('return \'d\'',
                      'return array(\'a\', \'b\')',
                      'return array(1, 2, 3, 4)',
                      'return new stdclass',
                     );

$expected_not = array('return function () { /**/ }',
                     );

?>