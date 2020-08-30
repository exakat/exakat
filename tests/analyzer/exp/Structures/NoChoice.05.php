<?php

$expected     = array('$b ? $c[1] + 1 : $c[1] + 1',
                     );

$expected_not = array('$b ? yield 1 : yield 2 ',
                      '$b ? function ($a) { /**/ } : function ($b) { /**/ } ',
                      'if ($b) { /**/ } else { /**/ } ',
                     );

?>