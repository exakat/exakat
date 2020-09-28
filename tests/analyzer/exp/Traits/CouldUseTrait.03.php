<?php

$expected     = array('class WithFoo1 { /**/ } ',
                      'class WithStaticFoo2 { /**/ } ',
                     );

$expected_not = array('class WithFoo2 { /**/ } ',
                      'class WithStaticFoo1 { /**/ } ',
                     );

?>