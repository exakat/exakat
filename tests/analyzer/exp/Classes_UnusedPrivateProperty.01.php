<?php

$expected     = array('private $privateM2', 
                      'private static $privateStaticM32', 
                      'private static $privateStaticM42', 
                      'private static $privateStaticM52', 
                      'private static $privateStaticM62', 
                      'private static $privateStaticM72');

$expected_not = array('private private$privateM', 
                      'private static $privateStaticM3', 
                      'private static $privateStaticM4', 
                      'private static $privateStaticM5', 
                      'private static $privateStaticM6', 
                      'private static $privateStaticM7');

?>