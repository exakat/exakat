<?php

$expected     = array('$_COOKIE[\'g\'] == A', 
                      '$_POST[\'d\'] == 2',
                     );

$expected_not = array('$_post[\'e\'] == 2',
                      '$_COOKIE[\'f\'] === 2',
                     );

?>