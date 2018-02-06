<?php

$expected     = array('include_once DIR . \'/INCLUDE.php\'',
                      'include (__DIR__ . \'/../INCLUDE.php\')',
                      'include_once x::DIR . \'/INCLUDE.php\'',
                      'include_once x::DIR . \'/inc/INCLUDE.php\'',
                      'include_once \\DIR_FULL . \'/INCLUDE.php\'',
                      'include_once x::DIR . \'/INC/INCLUDE.php\'',
                      'include_once \\DIR_FULL . \'/inc/INCLUDE.php\'',
                      'include_once \\DIR_FULL . \'/INC/include.php\'',
                      'include_once x::DIR . \'/INC/include.php\'',
                      'include_once \\DIR_FULL . \'/INC/INCLUDE.php\'',
                      'include_once DIR . \'/INC/include.php\'',
                      'include_once DIR . \'INCLUDE.php\'',
                      'include_once DIR . \'/INC/INCLUDE.php\'',
                      'include_once DIR . \'/inc/INCLUDE.php\'',
                     );

$expected_not = array('include_once DIR . \'include.php\'',
                      'include_once x::DIR . \'include.php\'',
                      'include_once \\DIR_FULL . \'include.php\'',
                     );

?>