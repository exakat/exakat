<?php

$expected     = array('error_reporting(12)',
                      'error_reporting(1 + 3)',
                     );

$expected_not = array('error_reporting(E_ALL)',
                      'error_reporting(0)',
                     );

?>