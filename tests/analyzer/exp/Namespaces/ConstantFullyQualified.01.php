<?php

$expected     = array('define(\'\\\\full\\\\namespace\\\\path\', \'value\')',
                     );

$expected_not = array('define(\'full\\\\ns\\path\', \'value2\')',
                      'define(\'noNSS\', \'value\')',
                     );

?>