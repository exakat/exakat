<?php

$expected     = array('$v1',
                      '$$v2',
                      '$v2',
                      '${$v3 . $v4}',
                      '$v3',
                      '$v4',
                     );

$expected_not = array('$p1',
                      '$p2',
                      '$p3',
                      '$p4',
                      '$p5',
                      '$p6',
                      '$p7',
                      '$p8',
                      '$p9',
                      '$p10',
                     );

?>