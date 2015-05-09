<?php

$expected     = array('public $unused = 2',
                      'public static $usedButStatic = 4');

$expected_not = array('$used',
                      '$usedInside',
                      '$usedButStatic'
);

?>