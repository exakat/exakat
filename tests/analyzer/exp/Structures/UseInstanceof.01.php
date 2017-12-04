<?php

$expected     = array('\'Function\' == get_class($x)',
                      'get_class($x) == \'Function\'',
                     );

$expected_not = array('get_class($xa)',
                     );

?>