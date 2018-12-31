<?php

$expected     = array('bar::$buggy = &$f',
                      'bar::$OK2 = &$f',
                     );

$expected_not = array('bar::$OK2 = &$f',
                      'bar::$OK3 = 3',
                      'bar::$OK3 = 1',
                     );

?>