<?php

$expected     = array('include \'../../nonexistant.php\'',
                      'include \'../../../nonexistant.php\'',
                      'include \'../nonexistant.php\'',
                      'include \'./nonexistant.php\'',
                      'include \'./nonexistant.php\'',
                      'include \'./nonexistant.php\'',
                      'include \'./nonexistant.php\'',
                      'include \'../nonexistant.php\'',
                      'include \'../nonexistant.php\'',
                      'include \'../../nonexistant.php\'',
                      'include \'../include_a.php\'',
                      'include \'../../include_a.php\'',
                      'include \'../include_b.php\'',
                     );

$expected_not = array('include \'./include.php\'',
                      'include "./include.$php"',
                      'include \'./include_a.php\'',
                      'include \'./include_b.php\'',
                      'include \'./include_c.php\'',
                      'include \'../../../include.php\'',
                     );

?>