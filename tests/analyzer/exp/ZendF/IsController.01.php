<?php

$expected     = array('class AController33 extends AController32 { /**/ } ',
                      'class AController21 extends \\Zend_Controller_Action { /**/ } ',
                      'class AController31 extends \\Zend_Controller_Action { /**/ } ',
                      'class AController32 extends AController31 { /**/ } ',
                      'class AController22 extends AController21 { /**/ } ',
                      'class AController extends Zend_Controller_Action { /**/ } ',
                     );

$expected_not = array('class NotAControllerWithExtends extends A { /**/ } ',
                      'class NotAController { /**/ } ',
                     );

?>