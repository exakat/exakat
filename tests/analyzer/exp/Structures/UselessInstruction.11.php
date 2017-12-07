<?php

$expected     = array('new $x1',
                      'new $x3',
                      'new $x4',
                      'new $x2',
                      'new $x1 instanceof X',
                      '(new $x2) instanceof X',
                     );

$expected_not = array('$y = new $x5',
                     );

?>