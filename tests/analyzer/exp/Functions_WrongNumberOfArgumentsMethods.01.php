<?php

$expected     = array('$other->version( )',
                      '$swift->version( )',
                      'other::version( )',
                      'swift::version( )',
                      'tooMany::version(1, 2, 3, 4, 5)',
                      '$tooMany->version(1, 2, 3, 4, 5)',
                      );

$expected_not = array('enough::version(1, 2)',
                      '$enough->version(1, 2)',
                      );

?>