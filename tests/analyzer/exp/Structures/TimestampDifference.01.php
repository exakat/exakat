<?php

$expected     = array('microtime( ) - $g',
                      'microtime(true) - $h',
                      'time( ) - START_TIME',
                      'time( ) - START_TIME',
                      'START_TIME - time( )',
                     );

$expected_not = array('START_TIME - $o->time()',
                     );

?>