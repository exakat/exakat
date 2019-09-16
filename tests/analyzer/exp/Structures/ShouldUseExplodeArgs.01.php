<?php

$expected     = array('explode(\'a1\', $string)',
                      'explode(\'a4\', $string)',
                      'explode(\'a6\', $string)',
                     );

$expected_not = array('explode(\'a2\', $string)',
                      'explode(\'a3\', $string)',
                      'explode(\'a5\', $string)',
                     );

?>