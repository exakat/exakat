<?php

$expected     = array('sprintf(\' a %s \', $a1, $a2, $a3)',
                      '\\sprintf(\' a %s \', $a1, $a2)',
                      'printf(\' a %%s %;\', $a1, $a2, $a3)',
                      'printf(\' a %%s \', $a1, $a2)',
                      'printf(\' a %%s \', $a1)',
                      'printf(\' a %Y \', $a1)',
                     );

$expected_not = array('printf(\' a %%s %;\')',
                      'printf(\' a %%s \', $a1, $a2)',
                     );

?>