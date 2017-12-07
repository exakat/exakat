<?php

$expected     = array('function __construct($read3a, &$written3a) { /**/ } ',
                      'function __construct($read1, &$written1) { /**/ } ',
                      'function x2($read2a, &$written2a) { /**/ } ',
                     );

$expected_not = array('function x3($read3b, &$written3b) { /**/ } ',
                     );

?>