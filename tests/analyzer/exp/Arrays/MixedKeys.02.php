<?php

$expected     = array('array(\'ONE\' => ONE, 2, \'three\' => z::THREE, 4, FIVE => 5, 6)',
                     );

$expected_not = array('array(\'ONE\' => ONE, 2, \'three\' => z::THREE, 4, 5, 6)',
                      'array(\'ONE\' => ONE, 2, \'three\' => z::THREE, 4, $five => 5, 8)',
                     );

?>