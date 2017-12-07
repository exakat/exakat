<?php

$expected     = array('\\ArrayAccess',
                     );

$expected_not = array('$a->b[$c]',
                      '$a::$b[$c]',
                      'A::$b',
                      '\\A::$b::$C',
                     );

?>