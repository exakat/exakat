<?php

$expected     = array('X $x',
                      '\\X $x',
                     );

$expected_not = array('\\I $i',
                      'I $i',
                      'UNKNOWN $u',
                      '\\UNKNOWN $u',
                      'string $s',
                     );

?>