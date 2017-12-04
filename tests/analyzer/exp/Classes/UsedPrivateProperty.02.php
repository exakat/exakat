<?php

$expected     = array('$privateM = 1',
                      '$privateStaticM3 = 3',
                      '$privateStaticM4 = 5',
                      '$privateStaticM5 = 7',
                      '$privateStaticM6 = 9',
                      '$privateStaticM7 = 11',
                     );

$expected_not = array('$privateStaticM72 = 12',
                      '$privateStaticM62 = 10',
                      '$privateStaticM52 = 8',
                      '$privateStaticM42 = 6',
                      '$privateStaticM32 = 4',
                      '$privateM2 = 2',
                     );

?>