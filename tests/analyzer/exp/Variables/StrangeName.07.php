<?php

$expected     = array('${42}',
                      '${[2]}',
                      '${rand( ) % 2 == 0}',
                      '${true}',
                      '${43}',
                      '${1.3}',
                      '${(bool) \'C\'}',
                     );

$expected_not = array('$value',
                     );

?>