<?php

$expected     = array('require (\'c/b.php\')',
                      'include_once (\'c/d/b.php\')',
                      'require_once (\'c/d/e/b.php\')',
                      'include \'b.php\'',
                     );

$expected_not = array('require (\'c/a.php\')',
                      'include_once (\'c/d/a.php\')',
                      'require_once (\'c/d/e/a.php\')',
                      'include \'a.php\'',
                     );

?>