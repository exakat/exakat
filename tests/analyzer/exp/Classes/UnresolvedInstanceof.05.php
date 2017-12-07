<?php

$expected     = array('$d instanceof \\xxx',
                      '$d instanceof xxy',
                     );

$expected_not = array('$a instanceof \\Iterator',
                      '$b instanceof \\Stdclass',
                      '$c instanceof Iterator',
                     );

?>