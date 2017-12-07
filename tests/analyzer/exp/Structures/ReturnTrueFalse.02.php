<?php

$expected     = array('$a = ($b == 2) ? true : false',
                      '$a = ($b == 3) ? false : true',
                     );

$expected_not = array('$a = ( $b == 4 ) ? false : true',
                     );

?>