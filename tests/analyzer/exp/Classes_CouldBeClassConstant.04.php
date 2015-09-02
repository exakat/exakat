<?php

$expected     = array('private $realCouldBe = 3.3', 
                      'public $intCouldBe = 1'
);

$expected_not = array('public $nullCouldNotBe = null',
                      'public $staticconstantCouldNotBe = self::NONE',
                      'public $undefinedCouldNotBe');

?>