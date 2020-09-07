<?php

$expected     = array('$b ? 2 : x::I', 
                      '$b ? 4 : ($x = x::I)',
                     );

$expected_not = array('$a ? \' \' : x::I',
                      '$b ? 4 : 1 + 5',
                     );

?>