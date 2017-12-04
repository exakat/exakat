<?php

$expected     = array('$implicitGlobal',
                     );

$expected_not = array('$_POST',
                      '${$type}',
                      '$x[2]',
                     );

?>