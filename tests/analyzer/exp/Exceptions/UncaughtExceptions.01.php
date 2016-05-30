<?php

$expected     = array('class G extends \Exception',
                      );

$expected_not = array('class A extends \Exception',
                      'class B extends A',
                      'class C extends B',
                      'class D extends C',
                      'class F extends C',
                      'class E');

?>