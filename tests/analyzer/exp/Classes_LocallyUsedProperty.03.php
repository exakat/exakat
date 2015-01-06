<?php

$expected     = array('private $localyUsed = 1');

$expected_not = array('private $usedInChild = 2',
                      'private $unused = 3',
                      'private $usedInGrandChild = 4');

?>