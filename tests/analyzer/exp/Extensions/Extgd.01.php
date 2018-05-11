<?php

$expected     = array('imagecreatefrompng("images/button1.png")',
                      'imagecolorallocate($im, 220, 210, 60)',
                      'imagesx($im)',
                      'imagestring($im, 3, $px, 9, $string, $orange)',
                      'imagepng($im)',
                      'imagedestroy($im)',
                     );

$expected_not = array('imagedestroy($im2)',
                     );

?>