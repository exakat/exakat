<?php

$expected     = array('$string[1][-1]',
                     );

$expected_not = array('$string[+-2][1]',
                      '$string[+-3]->a',
                      '$string[+-4]::$b',
                     );

?>