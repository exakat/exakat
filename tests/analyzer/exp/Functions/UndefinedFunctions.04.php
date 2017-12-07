<?php

$expected     = array('undefined(\'dfe\')',
                     );

$expected_not = array('include \'a.php\'',
                      'include_once \'ab.php\'',
                      'require(\'ac.php\')',
                      'require_once(\'abd.php\')',
                      'echo 1',
                      'print 2',
                     );

?>