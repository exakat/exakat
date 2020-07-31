<?php

$expected     = array(array('key' => 'function foo1($a) { /**/ } ',
                            'value'=> 2,
                            ),
                      array('key' => 'function foo2a( ) { /**/ } ',
                            'value'=> 2,
                            ),
                      array('key' => 'function foo2b( ) { /**/ } ',
                            'value'=> 3,
                            ),
                      array('key' => 'function foo3a( ) { /**/ } ',
                            'value'=> 4,
                            ),
                      array('key' => 'function foo3b( ) { /**/ } ',
                            'value'=> 4,
                            ),
                      array('key' => 'function foo3c( ) : int { /**/ } ',
                            'value'=> 4,
                            ),
                      array('key' => 'function foo3d($a = 2) { /**/ } ',
                            'value'=> 3,
                            ),
                     );

$expected_not = array(array('key' => 'function foo0( ) { /**/ } ',
                            'value'=> 1,
                            ),
                     );

?>
