<?php

$expected     = array('\\print_r($OK)',
                      'VAR_DUMP($OK)',
                     );

$expected_not = array('print_r($ko_static)',
                      'print_r($ko_method)',
                     );

?>