<?php

$expected     = array('foo(a,  )',
                      'foo3(b,  )',
                      'foo4(d,  )',
                     );

$expected_not = array('foo2(a,c)',
                      'list($a, $b, )',
                      '[$a, $b, ]',
                      'array(1, 2, 3, )',
                      '[$a, $c, ]',
                     );

?>