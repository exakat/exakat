<?php

$expected     = array('crypt(function ($a) { /**/ } )', 
                      'crypt(<<<\'BC\'

BC)', 
                      'crypt(1)', 
                      'crypt(__FILE__)', 
                      'crypt(false)', 
                      'crypt(<<<B

B
)',                    
                      'crypt(true)', 
                      'crypt(1.1)', 
                      'crypt(-2)');

$expected_not = array();

?>