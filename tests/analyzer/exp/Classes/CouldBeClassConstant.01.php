<?php

$expected     = array('public $sBCArrayp = array(1, 2, 3)',
                      'public $sBCIntegerRp = 1',
                     );

$expected_not = array('public $sBCIntegerRWp = 1',
                      'public $sBCIntegerWp = 1',
                      'private $sBCArray = array(1, 2, 3)',
                      'private $sBCIntegerR = 1',
                      'public $sBCIntegerRW = 1',
                      'public $sBCIntegerW = 1',
                     );

?>