<?php

$expected     = array('include (\'otheri.php\')',
                      'include_once (\'otheric.php\')',
                      'require (\'otherr.php\')',
                      'require_once (\'otherrc.php\')',
                      'require 2',
                     );

$expected_not = array('require(\'a method\')',
                      'include_once(\'a static method\')',
                     );

?>