<?php

$expected     = array('list(\'a\' => $oneBit, \'b\' => $twoBit, \'c\' . \'d\' => $threeBit)',
                      'list(1 => $oneBit, 2 => $twoBit, 3 => $threeBit)',
                     );

$expected_not = array('list($a, $b, $c)',
                     );

?>