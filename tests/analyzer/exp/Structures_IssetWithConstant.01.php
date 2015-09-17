<?php

$expected     = array('isset(X)',
                      'isset(X[$a])',
                      'isset(Y::X)',
                      'isset(Y::X[$b])'
);

$expected_not = array('isset(Y::$x[$b])');

?>