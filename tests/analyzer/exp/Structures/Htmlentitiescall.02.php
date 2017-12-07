<?php

$expected     = array('htmlentities($allMissing)',
                     );

$expected_not = array('$x->htmlentities()',
                      'Stdclass::htmlentities(1, 2)',
                     );

?>