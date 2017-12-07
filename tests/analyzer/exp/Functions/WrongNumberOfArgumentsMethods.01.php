<?php

$expected     = array('$other->ini_set( )',
                      '$swift->ini_set( )',
                      'other::ini_set( )',
                      'swift::ini_set( )',
                      'tooMany::ini_set(1, 2, 3, 4, 5)',
                      '$tooMany->ini_set(1, 2, 3, 4, 5)',
                     );

$expected_not = array('enough::ini_set(1, 2)',
                      '$enough->ini_set(1, 2)',
                     );

?>