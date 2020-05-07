<?php

$expected     = array('$a1', 
                      '$a2', 
                      'fn (C $b2) => new C( )',
                      'function (C $b) { /**/ } ',
                     );

$expected_not = array('$dd',
                      '$dd2',
                     );

?>