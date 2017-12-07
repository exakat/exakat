<?php

$expected     = array('spliti(\'a\', \'abababababab\')',
                     );

$expected_not = array('spliti(',
                      ', A::f(\'b\'))',
                      'spliti(B::$c, \'asdfafasdfasfsf\')',
                     );

?>