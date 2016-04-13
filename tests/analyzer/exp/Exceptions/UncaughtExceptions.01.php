<?php

$expected     = array('class D extends C');

$expected_not = array('class A extends \Exception',
                      'class B extends A',
                      'class C extends B',
                      'class E',
                      'class F extends C');

?>