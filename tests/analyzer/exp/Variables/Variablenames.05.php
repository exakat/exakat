<?php

$expected     = array('$b',
                      '$x',
                     );

$expected_not = array('$children',
                      'Y',
                      '$this', // This is not considered a normal variable
                     );

?>