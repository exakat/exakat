<?php

$expected     = array('function ($a1) : void { /**/ } ', 
                      'fn ($a2) : int => 1',
                      'fn (C $b2) => new C( )',
                      'function (C $b) { /**/ } ',
                     );

$expected_not = array('$dd',
                      '$dd2',
                     );

?>