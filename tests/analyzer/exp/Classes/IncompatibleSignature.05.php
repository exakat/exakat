<?php

$expected     = array('function __construct($a = 1) { /**/ } ',
                     );

$expected_not = array('function __construct($a = 1, $b = 2) { /**/ } ',
                      'function __construct($a = 1, $b = 2, $c = 3) { /**/ } ',
                     );

?>