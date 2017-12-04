<?php

$expected     = array('static private $spp, $spp2, $spp3',
                      'private static $psp, $psp2, $psp3',
                      'static $sp, $sp2, $sp3',
                     );

$expected_not = array('static $sv, $sv2, $sv3',
                     );

?>