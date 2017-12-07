<?php

$expected     = array('$unreachable1++',
                      '$unreachable2++',
                      '$unreachable3++',
                      '$unreachable4++',
                      '$unreachable5++',
                      '$unreachable6++',
                     );

$expected_not = array('$reachable1++',
                      '$reachable2++',
                      '$reachable21++',
                      '$reachable3++',
                      '$reachable42++',
                      '$reachable521++',
                      '$reachable6++',
                      '$reachable72++',
                      '$reachable821++',
                     );

?>