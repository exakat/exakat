<?php

$expected     = array('$reallyUnused2',
                      '$reallyUnused = 1',
                     );

$expected_not = array('$inGlobalScope = 1',
                      '$notAProperty = null',
                      '$notAProperty2',
                      '$notAProperty3 = 3',
                     );

?>