<?php

$expected     = array('pOW(8, 9)',
                      'pOw(6, 7)',
                      'POW(3, 4)',
                      'pow(1, 2)',
                      );

$expected_not = array('\\POW(pOw(13, 14), 15)',
                      '\\POw(pOW(8, 9), 10)',
                      '\\Pow(POW(3, 4), 5)');

?>