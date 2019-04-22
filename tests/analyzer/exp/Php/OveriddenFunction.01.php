<?php

$expected     = array('dirnAME(1, 2, 3)',
                      'dirname(1, 2)',
                     );

$expected_not = array('\\dirname(1, 2);',
                      'range(3, 4)',
                      'implode(\'a\', range(3, 4))',
                     );

?>