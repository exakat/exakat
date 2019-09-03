<?php

$expected     = array('strip_tags($input, \'<br/>\')',
                      'strip_tags($input, \'<br />\')',
                     );

$expected_not = array('strip_tags($input, \'br\')',
                      'strip_tags($input, \'<BR>\')',
                     );

?>