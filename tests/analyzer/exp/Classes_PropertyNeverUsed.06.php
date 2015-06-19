<?php

$expected     = array('static $staticPropertyUnused = 5');

$expected_not = array('protected $usedProtectedByAbove',
                      'protected $usedProtectedByBelowC',
                      'protected $usedProtectedByBelowE',
                      'protected $usedProtectedByBelowF'
);

?>