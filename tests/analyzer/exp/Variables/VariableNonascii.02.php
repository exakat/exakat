<?php

$expected     = array('$像',
                     );

$expected_not = array('...$a',
                      '${$x}',
                      '$$像',
                      '&$b',
                      '$$x',
                      '&$w',
                      '...$c',
                      '$p',
                     );

?>