<?php

$expected     = array('count(\'1234\')',
                     );

$expected_not = array('count(array(1,2,3,4))',
                      'count($unsetVar)',
                     );

?>