<?php

$expected     = array('var $x', 
                      'var $x1 = 1',
                      'var $x4',
                      'var $x3',
                      'var $x2',
                      'var $x7 = 3',
                      'var $x6 = 2',
                      'var $x5 = 1');
$expected_not = array('public $p1',
                      'private $p2 = 1',
                      'protected $p3',
                      'protected $p4 = 1');

?>