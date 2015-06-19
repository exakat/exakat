<?php

$expected     = array('protected $localyUsed = 1');

$expected_not = array('protected $usedInChild = 2',
                      'protected $unused = 3',
                      'protected $usedInGrandChild = 4');

?>