<?php

$expected     = array('static $staticReadWithSelf = array(1, 2, 3)',
                      'static $staticReadWithStatic = array(1, 2, 4)',
                     );

$expected_not = array('static $staticModifiedWithSelf = array(1, 2, 5)',
                      'static $staticModifiedWithStatic = array(1, 2, 6)',
                      'static $staticModifiedWithThis = array(1, 2, 7)',
                     );

?>