<?php

$expected     = array('explode(\',\', self::A)',
                     );

$expected_not = array('\'C\' . $a[\'b\']',
                      '\'D\' . $a',
                      'explode(',
                      ', B::A)',
                      'explode(',
                      ', self::A2)',
                     );

?>