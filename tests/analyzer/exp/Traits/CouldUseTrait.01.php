<?php

$expected     = array('class WithFoo { /**/ } ',
                     );

$expected_not = array('class NoNothing { /**/ } ',
                      'class WithFooAndT { /**/ } ',
                      'class WithT { /**/ } ',
                     );

?>