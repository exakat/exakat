<?php

$expected     = array('function __construct(private $a1 = [ ], public $b) { /**/ } ',
                     );

$expected_not = array('function __construct(private $a2 = [ ], public $b = 2) { /**/ } ',
                      'function __construct(private $a3, public $b = 2) { /**/ } ',
                     );

?>