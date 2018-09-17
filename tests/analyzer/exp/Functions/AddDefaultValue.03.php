<?php

$expected     = array('function __construct($x) { /**/ } ',
                      'function __construct($x, $y, $z) { /**/ } ',
                      'function __construct($x) { /**/ } ',
                      'function __construct($x) { /**/ } ',
                     );

$expected_not = array('function __construct($x = null) { /**/ } ',
                      'function __construct($x = 1) { /**/ } ',
                      'function __construct($x) { /**/ } ',
                      'function __construct($x) { /**/ } ',
                     );

?>