<?php

$expected     = array('private static $privateStaticM72 = 12', 
                      'private static $privateStaticM62 = 10', 
                      'private static $privateStaticM52 = 8', 
                      'private static $privateStaticM42 = 6', 
                      'private static $privateStaticM32 = 4', 
                      'private $privateM2 = 2');

$expected_not = array('private static $privateStaticM7 = 11', 
                      'private static $privateStaticM6 = 9', 
                      'private static $privateStaticM5 = 7', 
                      'private static $privateStaticM4 = 5', 
                      'private static $privateStaticM3 = 3', 
                      'private $privateM = 1');

?>