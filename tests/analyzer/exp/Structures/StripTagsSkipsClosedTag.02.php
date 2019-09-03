<?php

$expected     = array('strip_tags($input, <<<HHH
<br />
HHH)',
                      'strip_tags($input, A)',
                     );

$expected_not = array('strip_tags($input, \'br\')',
                      'strip_tags($input, \'<BR>\')',
                     );

?>