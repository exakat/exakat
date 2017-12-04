<?php

$expected     = array('$x .= \'e\'',
                      '$x .= \'d\'',
                     );

$expected_not = array('$x .= foo($a)',
                     );

?>