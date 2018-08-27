<?php

$expected     = array('array_filter($a, array(\'x\', \'cube2\'))',
                     );

$expected_not = array('array_filter($a, array(\'x\', \'cube1\'))',
                      'array_filter($a, array(\'x\', \'cube3\'))',
                      'array_filter($a, array(\'x\', \'cube4\'))',
                     );

?>