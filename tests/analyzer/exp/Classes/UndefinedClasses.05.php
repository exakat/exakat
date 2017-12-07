<?php

$expected     = array('UndefinedClass::y( )',
                     );

$expected_not = array('$x::y( )',
                      '$x[3]::$z',
                      '$x[4][5]::a',
                     );

?>