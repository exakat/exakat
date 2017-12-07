<?php

$expected     = array('$unreachable1++',
                      '$unreachable2++',
                      '$unreachable3++',
                     );

$expected_not = array('$reachable1++',
                      '$reachable2++',
                      '$reachable21++',
                      '$reachable3++',
                      '$reachable4++',
                      '$reachable41++',
                      '$reachable5++',
                      '$reachable51++',
                     );

?>