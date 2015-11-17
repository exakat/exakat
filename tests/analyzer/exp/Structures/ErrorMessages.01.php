<?php

$expected     = array("'Yes'",
                      '4',
                      'mysql_query( )',
                      "'Exception Messages'",
                      '"Exception $y Messages"',
                      "'Exception ' . \$x . 'FullNsPath'",
                      "'Exception myException'",
                      );

$expected_not = array('a');

?>