<?php

$expected     = array('do { /**/ } while($a2 > 1)',
                      'do { /**/ } while($a4 > 1)',
                      'do { /**/ } while($a1 > 1)',
                      'do { /**/ } while($a3 > 1)',
                     );

$expected_not = array('do { /**/ } while($a5 > 1)',
                     );

?>