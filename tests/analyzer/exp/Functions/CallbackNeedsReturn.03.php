<?php

$expected     = array('array_filter($a, function ($n2) { /**/ } )',
                      'array_filter($a, "cube2")',
                      'array_filter($a, array(\'x\', \'cube2\'))',
                     );

$expected_not = array('array_filter($a, function ($n) { /**/ } )',
                      'array_filter($a, "cube")',
                      'array_filter($a, array(\'x\', \'cube\'))',
                     );

?>