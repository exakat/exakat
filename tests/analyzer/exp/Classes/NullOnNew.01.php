<?php

$expected     = array('new PDO',
                      'new \\Finfo( )',
                      'new NumberFormatter( )',
                      'new MessageFormatter(\'en_US\', \'{this was made intentionally incorrect}\')',
                     );

$expected_not = array('IntlDateFormatter( )',
                      'new \\B\\NumberFormatter',
                      'new \\C\\NumberFormatter',
                      'new \\Stdclass',
                     );

?>