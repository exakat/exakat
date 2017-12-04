<?php

$expected     = array('interface i { /**/ } ',
                      'interface i2 extends i { /**/ } ',
                     );

$expected_not = array('interface i3 extends j { /**/ } ',
                     );

?>