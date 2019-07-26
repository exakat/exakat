<?php

$expected     = array('switch ([\'foo2\']) { /**/ } ',
                     );

$expected_not = array('switch ([\'foo1\']) { /**/ } ',
                      'switch ([\'foo3\']) { /**/ } ',
                      'switch ([\'foo4\']) { /**/ } ',
                     );

?>