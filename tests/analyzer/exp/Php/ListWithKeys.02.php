<?php

$expected     = array('[\'a\' => $oneBit, \'b\' => $twoBit, \'c\' . \'d\' => $threeBit]',
                      '[1 => $oneBit, 2 => $twoBit, 3 => $threeBit]',
                     );

$expected_not = array('[$a, $b, $c]',
                     );

?>