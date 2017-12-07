<?php

$expected     = array('define(\'\\\\full\\\\ns\\\\path\', \'value1\')',
                     );

$expected_not = array('define(\'full\\ns\\path\', \'value2\')',
                      'define(\'noNSS\', \'value\')',
                     );

?>