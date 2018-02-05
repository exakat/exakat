<?php

$expected     = array('function __construct($d) { /**/ } ',
                     );

$expected_not = array('function __construct($a) { /**/ } ',
                      'function __construct($c) { /**/ } ',
                      'function __construct($b) { /**/ } ',
                      'function other() { /**/ } ',
                     );

?>