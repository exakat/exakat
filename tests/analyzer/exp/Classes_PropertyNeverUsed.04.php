<?php

$expected     = array('static $staticPropertyUnused = 5');

$expected_not = array('static $staticPropertySelf = 1',
                      'static $staticPropertyStatic = 2',
                      'static $staticPropertyx = 3',
                      'static $staticPropertyxFNS = 4'
                      );

?>