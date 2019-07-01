<?php

$expected     = array('substr($a, 1, -1)',
                      'substr($a, 0, -1)',
                      'substr($a, 1)',
                     );

$expected_not = array('substr($a, 2, 1)',
                     );

?>