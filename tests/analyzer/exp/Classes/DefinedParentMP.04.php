<?php

$expected     = array('parent::$someAPropertyInCL',
                      'parent::$someBPropertyInCL',
                      'parent::$someCPropertyInCL',
                     );

$expected_not = array('parent::$someDPropertyLost',
                     );

?>