<?php

$expected     = array('$c',
                      '$a[\'x\']',
                      '$d->g',
                     );

$expected_not = array('$b',
                      '$a',
                      '\'x\'',
                      'Stdclass::c',
                     );

?>