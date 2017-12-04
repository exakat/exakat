<?php

$expected     = array('function ($y) { /**/ } ',
                      'function ($b) use ($x) { /**/ } ',
                     );

$expected_not = array('function C ($b) { return 4; }',
                      'function Cx ($b) { return 5; }',
                     );

?>