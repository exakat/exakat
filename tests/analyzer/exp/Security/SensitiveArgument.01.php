<?php

$expected     = array('$c',
                      '$a[\'x\']',
                      '$d->g',
                     );

$expected_not = array('$b',
                      'Stdclass::c',
                     );

?>