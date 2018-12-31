<?php

$expected     = array('\\invalidArgumentException(\'A\')',
                      'Exception(\'D\')',
                     );

$expected_not = array('\\B\\invalidArgumentException(\'V\')',
                      '\\NotPhpException(\'V\')',
                      '$v',
                      '$e[1]',
                     );

?>