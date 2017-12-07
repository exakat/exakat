<?php

$expected     = array('static $sp = 1',
                      'private static $psp = 2',
                      'static public $spp = 3',
                     );

$expected_not = array('protected $p = 4',
                      'static $sv = 4',
                     );

?>