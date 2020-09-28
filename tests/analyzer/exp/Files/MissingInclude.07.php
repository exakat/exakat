<?php

$expected     = array('include \'a.php\'',
                      'include \'b.php\'',
                     );

$expected_not = array('include $a',
                      'include $a[1]',
                      'include $a->m( )',
                     );

?>