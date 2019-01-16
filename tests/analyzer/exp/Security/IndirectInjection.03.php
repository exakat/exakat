<?php

$expected     = array('$b . \' yes \' . $a', 
                      'eval($a)',
                     );

$expected_not = array('EVAL($a)',
                      '$a.\' yes \'.$a',
                     );

?>