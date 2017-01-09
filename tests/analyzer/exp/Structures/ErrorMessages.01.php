<?php

$expected     = array("'Yes'",
                      '4',
                      'mysql_query( )',
                      '\'Exception myException via variable\'', 
                      '\'Exception A myException\'',
                       "'Exception ' . \$x . 'FullNsPath'",
                      "'Exception myException'",
                      );

$expected_not = array('a');

?>