<?php

$expected     = array('protected $unusedProtected',
                      'protected $unusedProtectedByBelowD',
                      );

$expected_not = array('protected $usedProtectedByAbove',
                      'protected $usedProtectedByBelowC',
                      'protected $usedProtectedByBelowE',
                      'protected $usedProtectedByBelowF'
);

?>