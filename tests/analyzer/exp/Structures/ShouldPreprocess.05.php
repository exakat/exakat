<?php

$expected     = array('explode(\',\', \'A\')',
                     );

$expected_not = array('\'C\' . $a[\'b\']',
                      '\'D\' . $a',
                      'explode(\',\', self::A)',
                      'explode(\',\', self::A2)',
                      'explode(\',\', B::A)',
                     );

?>