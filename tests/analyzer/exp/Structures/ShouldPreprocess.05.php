<?php

$expected     = array('explode(\',\', B::A)',
                     );

$expected_not = array('\'C\' . $a[\'b\']',
                      '\'D\' . $a',
                      'explode(\',\', self::A)',
                      'explode(\',\', self::A2)',
                     );

?>