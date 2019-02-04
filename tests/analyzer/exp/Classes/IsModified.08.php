<?php

$expected     = array('A::$upper',
                      '$lower->b',
                      '$x->a',
                     );

$expected_not = array('$y-<y',
                      '$phpversion',
                     );

?>