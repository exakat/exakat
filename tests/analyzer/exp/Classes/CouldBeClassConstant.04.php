<?php

$expected     = array('public $intCouldBe = 1',
                      'var $intVarCouldBe = 1',
                      'public $floatCouldBe = 1.2',
                      'public $stringCouldBe = "1.2"',
                      'public $intM1CouldBe = 1, $intM2CouldBe = 1, $intM3CouldBe = 1',
                     );

$expected_not = array('private $realCouldBe = 3.3',
                      'public $nullCouldNotBe = null',
                      'public $staticconstantCouldNotBe = self::NONE',
                      'public $undefinedCouldNotBe',
                     );

?>