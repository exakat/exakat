<?php

$expected     = array('$results[$row[\'a\']][\'b\'][$row[\'c\']][0]',
                     );

$expected_not = array('$a::$s[$row[\'a\']][][$row[\'c5\']]',
                      '$a->s[$row[\'a\'][\'b\'][$row[\'c2\'] ]]',
                     );

?>