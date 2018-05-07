<?php

$expected     = array('sprintf(\' a %s %s \', $a1, $a3, ...$a2, ...$a4)',
                      'sprintf(\' a %s %s \', $a1, $a3, ...$a2)',
                     );

$expected_not = array('\\sprintf(\' a %s %s \', ...$a1)',
                      'sprintf(\' a %s %s \', ...$a1)',
                      'sprintf(\' a %s %s %s\', $a1, $a3, ...$a2)',
                      'sprintf(\' a %s %s \', ...$a1)',
                      '\\sprintf(\' a %s %s \', ...$a1)',
                     );

?>