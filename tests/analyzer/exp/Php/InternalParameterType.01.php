<?php

$expected     = array('crypt(function ($a) { /**/ } )', 
                      'crypt(1)', 
                      'crypt(false)', 
                      'crypt(true)', 
                      'crypt(1.1)', 
                      'crypt(-2)');

$expected_not = array('crypt(<<<\'BC\'

BC)',                 'crypt(<<<B

B
)',                    
                      'crypt(__FILE__)', 
                      'crypt("D $e f")'


);

?>