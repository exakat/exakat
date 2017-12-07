<?php

$expected     = array('\'Yes\'',
                      '4',
                      'mysql_query( )',
                      '\'Exception myException via variable\'',
                      '\'Exception A myException\'',
                      '\'Exception \' . $x . \'FullNsPath\'',
                      '\'Exception myException\'',
                      '"Exception $y Messages"',
                      '\'Exception Messages\'',
                     );

$expected_not = array('a',
                     );

?>