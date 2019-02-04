<?php

$expected     = array('$implicitGlobal',
                      '$implicitGlobal',
                     );

$expected_not = array('$_POST',
                      '${$type}',
                      '$x[2]',
                     );

?>