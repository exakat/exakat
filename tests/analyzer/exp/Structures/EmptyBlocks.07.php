<?php

$expected     = array('declare (ticks = 1) :  /**/  enddeclare',
                      'declare (ticks = 2)  { /**/ } ',
                      'declare (ticks = 4) :  /**/  enddeclare',
                     );

$expected_not = array('declare (ticks = 3)  { /**/ } ',
                     );

?>