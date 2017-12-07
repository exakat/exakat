<?php

$expected     = array('do { /**/ } while($a4)',
                      'do { /**/ } while($a5)',
                      'do /**/ while($a1)',
                      'do /**/ while($a2)',
                     );

$expected_not = array('do { /**/ } while($a6)',
                      'do /**/ while($a3)',
                     );

?>