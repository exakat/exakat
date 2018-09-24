<?php

$expected     = array('include_once (dirname(__DIR__, 2) . \'/abc.php\')',
                      'include_once (dirname(__DIR__, 2) . \'/include.php\')',
                      'require (dirname(__DIR__, 1) . \'/ab.php\')',
                      'require (dirname(__DIR__, 1) . \'/include.php\')',
                      'require_once (dirname(__DIR__) . \'/a.php\')',
                      'require_once (dirname(__DIR__) . \'a.php\')',
                      'require_once (dirname(__DIR__) . \'include.php\')',
                      'include_once (dirname(dirname(dirname(__DIR__))) . \'/abc.php\')',
                     );

$expected_not = array('require_once (dirname(__DIR__, 1) . \'/include.php\')',
                      'require_once (dirname(__DIR__) . \'/include.php\')',
                      'require_once (dirname(__DIR__) . \'/include.php\')',
                      'include_once (dirname(dirname(dirname(__DIR__))) . \'include.php\')',
                     );

?>