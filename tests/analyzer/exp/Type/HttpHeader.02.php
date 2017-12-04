<?php

$expected     = array('<<<HEADER
Content-Type: application/octet-stream
HEADER
',
                      '<<<\'HEADER\'
Max-Forwards: 34
custom-header: 33
custom-header-2: 34
HEADER',
                     );

$expected_not = array('Content-Type: application/octet-stream',
                      'Max-Forwards: 34
custom-header: 33
custom-header-2: 34',
                     );

?>