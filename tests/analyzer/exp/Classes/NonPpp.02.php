<?php

$expected     = array('var $varp = 2',
                      'static $staticp = 1',
                     );

$expected_not = array('private $varPrivate, $varPrivate2',
                      '$varPrivate',
                      '$varPrivate2',
                     );

?>