<?php

$expected     = array('customcall(\'D3\') or die(\'E3\')',
                     );

$expected_not = array('defined(\'D\') or die(\'E\')',
                      '!defined(\'D2\') or die(\'E2\')',
                     );

?>