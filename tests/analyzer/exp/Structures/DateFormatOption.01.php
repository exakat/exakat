<?php

$expected     = array('date(\'%R\')',
                     );

$expected_not = array('date(\'%r\')',
                      'date(\'%%r\')',
                      'date(\'%%%r\')',
                     );

?>