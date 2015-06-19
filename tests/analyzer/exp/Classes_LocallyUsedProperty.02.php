<?php

$expected     = array('public $localyUsed = 1');

$expected_not = array('public $usedInChild = 2',
                      'public $unused = 3',
                      'public $usedInGrandChild = 4');

?>