<?php

$expected     = array('var $x',
                      'var $x1 = 1',
                      'var $x5 = 1, $x6 = 2, $x7 = 3',
                      'var $x2, $x3, $x4',
                     );

$expected_not = array('public $p1',
                      'private $p2 = 1',
                      'protected $p3',
                      'protected $p4 = 1',
                     );

?>