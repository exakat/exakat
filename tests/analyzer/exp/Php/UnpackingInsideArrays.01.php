<?php

$expected     = array('array(1, ...$b, 2)',
                      '[1, ...$c, 3]',
                      '[... $c]',
                     );

$expected_not = array('[1, [... $c], 3]',
                      '$a->array(1, ...$d, 23)',
                     );

?>