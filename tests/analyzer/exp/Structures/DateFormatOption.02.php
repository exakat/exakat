<?php

$expected     = array('gmdate(\'%R\')',
                     );

$expected_not = array('gmdate(\'%r\')',
                      'gmdate(\'%%r\')',
                      'gmdate(\'%%%r\')',
                     );

?>