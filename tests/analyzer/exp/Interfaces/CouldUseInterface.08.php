<?php

$expected     = array('class y { /**/ } ',
                     );

$expected_not = array('class x implements countable { /**/ } ',
                      'class z implements \Countable { /**/ } ',
                      'class zz extends z { /**/ } ',
                     );

?>