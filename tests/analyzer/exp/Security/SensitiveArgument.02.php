<?php

$expected     = array('$b',
                      '$a[\'x\']',
                     );

$expected_not = array('$c',
                      '$d->g',
                      'Stdclass::c',
                     );

?>