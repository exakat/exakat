<?php

$expected     = array('public $unused = 2');

$expected_not = array('$used',
                      '$usedInside',
                      '$usedButStatic',
                      'public static $usedButStatic = 4'
);

?>