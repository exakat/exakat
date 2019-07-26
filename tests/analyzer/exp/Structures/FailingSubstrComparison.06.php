<?php

$expected     = array('substr($a, 0, 3) === D',
                     );

$expected_not = array('substr($a, 0, 3) === C',
                      'substr($a, 0, 3) === E',
                     );

?>