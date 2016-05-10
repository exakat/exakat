<?php

$expected     = array('Zend_Auth::getInstance( )', 
                      'class MyController extends Zend_Controller_Action', 
                      'class MyController2 extends MyController', 
                      'class myHelper extends Zend_View_Helper_Abstract');

$expected_not = array('Zend_Auth::getInstance( 1 )', 
                      'class realController extends Zend_Controller_Action', 
                      'class realHelper extends Zend_View_Helper_Abstract');

?>