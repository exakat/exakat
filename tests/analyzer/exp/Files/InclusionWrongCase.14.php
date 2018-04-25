<?php

$expected     = array( 'include \'include.PHP\'',
                     );

$expected_not = array('include \'include.php\'',
                      'dirname(__FILE__) . include \'include.php\'',
                      'dirname(__FILE__) . include \'include.PHP\'',
                     );

?>