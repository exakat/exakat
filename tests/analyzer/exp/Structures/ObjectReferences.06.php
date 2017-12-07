<?php

$expected     = array('Stdclass &$b',
                      '\\Stdclass &$b3 = null',
                      'Stdclass &$b2 = null',
                     );

$expected_not = array('array &$a',
                      'array &$a2',
                      'Callable &$c2',
                      'Callable &$c',
                     );

?>