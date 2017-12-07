<?php

$expected     = array('$string[-+1]',
                      '$string[-2]',
                      '$string{-1}',
                      '$string{-1}',
                      '$string{-2}',
                     );

$expected_not = array('$string[0]',
                      '$string{0}',
                      '$string[1]',
                      '$string{1}',
                     );

?>