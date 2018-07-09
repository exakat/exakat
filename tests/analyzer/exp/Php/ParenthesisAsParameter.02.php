<?php

$expected     = array('sort((getArray( )))',
                      'system($a, (getVar( )))',
                     );

$expected_not = array('sort(getArray( ))',
                      'system($a, getVar( ))',
                     );

?>