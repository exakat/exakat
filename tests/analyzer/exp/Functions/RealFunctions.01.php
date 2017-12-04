<?php

$expected     = array('function yf( ) { /**/ } ',
                      'function yfyf( ) { /**/ } ',
                     );

$expected_not = array('function yx(Stdclass $y = null, $yy = 2, Stdclass $yyy) { /**/ } ',
                      'function yt(Stdclass $y = null, $yy = 2, Stdclass $yyy) { /**/ } ',
                      'function yi() ',
                      'function ($a) { /**/ } ',
                     );

?>