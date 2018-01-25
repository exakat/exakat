<?php

$expected     = array('include \'./INCLUDE.php\'',
                      'include \'./include_A.php\'',
                      'include \'../include_A.php\'',
                      'include \'./include_B.php\'',
                      'include \'./include_C.php\'',
                      'include \'../include_B.php\'',
                      );

$expected_not = array('include \'./include.php\'',
                      'include \'./include_a.php\'',
                      'include \'../include_a.php\'',
                      'include \'./include_b.php\'',
                      'include \'./include_c.php\'',
                      'include \'../include_b.php\'',
                     );

?>