<?php

$expected     = array('include \'a.php\'',
                      'include \'b.php\'',
                      'include_once (\'c/d/b.php\')',
                      'include_once \'c/d/a.php\'', 
                      'require (\'c/b.php\')',
                      'require \'c/a.php\'', 
                      'require_once (\'c/d/e/b.php\')',
                      'require_once \'c/d/e/a.php\'', 
                     );

$expected_not = array('require (\'c/a.php\')',
                      'include_once (\'c/d/a.php\')',
                      'require_once (\'c/d/e/a.php\')',
                      'include \'a.php\'',
                     );

?>