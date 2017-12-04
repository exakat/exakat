<?php

$expected     = array('Zend_Auth::getInstance( )',
                      'class myHelper extends Zend_View_Helper_Abstract { /**/ } ',
                      'class MyController2 extends MyController { /**/ } ',
                      'class MyController extends Zend_Controller_Action { /**/ } ',
                     );

$expected_not = array('Zend_Auth::getInstance( 1 )',
                      'class realController extends Zend_Controller_Action { /**/ } ',
                      'class realHelper extends Zend_View_Helper_Abstract { /**/ } ',
                     );

?>