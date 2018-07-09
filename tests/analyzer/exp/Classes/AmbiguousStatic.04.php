<?php

$expected     = array('private static $mixtedStatic',
                      'private $mixtedStatic',
                     );

$expected_not = array('private $noneStatic',
                      'private static $allStatic',
                     );

?>