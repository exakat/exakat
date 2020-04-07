<?php

$expected     = array('private static $mixtedStatic',
                      'private $mixtedStatic',
                      'private static $varAndStatic',
                      'var X $varAndStatic',
                     );

$expected_not = array('private $noneStatic',
                      'private static $allStatic',
                     );

?>