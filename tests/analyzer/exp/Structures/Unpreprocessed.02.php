<?php

$expected     = array('explode(\'a\', \'abababababab\')',
                     );

$expected_not = array('explode(',
                      ', f(\'b\'))',
                      'explode($c, \'asdfafasdfasfsf\')',
                     );

?>