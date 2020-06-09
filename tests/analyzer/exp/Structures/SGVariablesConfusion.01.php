<?php

$expected     = array('static $a',
                      'global $a',
                      '$a2',
                      '$a4',
                     );

$expected_not = array('$a6',
                      '$b6',
                      '$c6',
                      '$a3',
                     );

?>