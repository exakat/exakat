<?php

$expected     = array('${42}',
                      '${[2]}',
                      '${rand( ) % 2 == 0}',
                      '${true}',
                      '${43}',
                     );

$expected_not = array('$value',
                     );

?>