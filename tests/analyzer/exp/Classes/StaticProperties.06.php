<?php

$expected     = array('static $sp',
                      'private static $psp',
                      'static private $spp',
                     );

$expected_not = array('static $sv',
                      'protected $pp',
                     );

?>