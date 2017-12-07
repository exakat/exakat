<?php

$expected     = array('static private $spp = 7, $spp2 = 8, $spp3 = 9',
                      'private static $psp = 4, $psp2 = 5, $psp3 = 6',
                      'static $sp = 1, $sp2 = 2, $sp3 = 3',
                     );

$expected_not = array('static $sv = 10, $sv2 = 11, $sv3 = 11',
                      'private $pp = 7, $pp2 = 8, $pp2 = 9',
                     );

?>