<?php

$expected     = array('json_encode(new StdClass(\'a\'))',
                      'json_encode(array(\'a\' => \'b\'))',
                     );

$expected_not = array('json_decode($a)',
                      'json_last_error_msg()',
                     );

?>