<?php

$expected     = array('private static $mixtedStatic = 3',
                      'private $mixtedStatic = 5',
                     );

$expected_not = array('private $noneStatic = 3',
                      'private static $allStatic',
                     );

?>