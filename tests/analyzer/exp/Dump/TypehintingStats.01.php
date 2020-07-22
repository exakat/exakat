<?php

$expected     = array(
                       array('key'    => 'totalArguments',
                            'value' =>'12',
                           ),

                      array('key'    => 'totalFunctions',
                            'value' =>'3',
                           ),

                      array('key'    => 'withTypehint',
                            'value' => '9',
                           ),

                      array('key'    => 'withReturnTypehint',
                            'value' =>'3',
                           ),

                      array('key'    => 'scalartype',
                            'value' =>'6',
                           ),

                      array('key'    => 'returnNullable',
                            'value' =>'0',
                           ),

                      array('key'    => 'argNullable',
                            'value' =>'2',
                           ),

                      array('key'    => '\float',
                            'value' =>'1',
                           ),

                      array('key'    => '\iterable',
                            'value' =>'1',
                           ),

                      array('key'    => '\void',
                            'value' =>'1',
                           ),

                      array('key'    => '\array',
                            'value' =>'1',
                           ),

                      array('key'    => '\callable',
                            'value' =>'1',
                           ),

                      array('key'    => '\string',
                            'value' =>'1',
                           ),

                      array('key'    => '\mixed',
                            'value' =>'2',
                           ),

                      array('key'    => '\numeric',
                            'value' =>'1',
                           ),

                      array('key'    => '\resource',
                            'value' =>'1',
                           ),

                      array('key'    => '\a',
                            'value' =>'1',
                           ),

                      array('key'    => 'allTotal',
                            'value' =>'4',
                           ),

                      array('key'    => 'allWithTypehint',
                            'value' =>'4',
                           ),

                      array('key'    => 'allWithReturnTypehint',
                            'value' =>'3',
                           ),

                      array('key'    => 'functionTotal',
                            'value' =>'1',
                           ),

                      array('key'    => 'functionWithTypehint',
                            'value' =>'1',
                           ),

                      array('key'    => 'functionWithReturnTypehint',
                            'value' =>'1',
                           ),

                      array('key'    => 'methodTotal',
                            'value' =>'2',
                           ),

                      array('key'    => 'methodWithTypehint',
                            'value' =>'2',
                           ),

                      array('key'    => 'methodWithReturnTypehint',
                            'value' =>'1',
                           ),

                      array('key'    => 'closureTotal',
                            'value' =>'1',
                           ),

                      array('key'    => 'closureWithTypehint',
                            'value' =>'1',
                           ),

                      array('key'    => 'closureWithReturnTypehint',
                            'value' =>'1',
                           ),

                      array('key'    => 'arrowfunctionTotal',
                            'value' =>'0',
                           ),

                      array('key'    => 'arrowfunctionWithTypehint',
                            'value' =>'0',
                           ),

                      array('key'    => 'arrowfunctionWithReturnTypehint',
                            'value' =>'0',
                           ),

                      array('key'    => 'classTypehint',
                            'value' => '0',
                           ),

                      array('key'    => 'interfaceTypehint',
                            'value' => '0',
                           ),

                      array('key'    => 'typedProperties',
                            'value' => '0',
                           ),

                      array('key'    => 'totalProperties',
                            'value' => '0',
                           ),

                      array('key'    => 'multipleTypehints',
                            'value' => '0',
                           ),
                     );

$expected_not = array(
                      array('key'    => 'arrowfunctionWithReturnTypehint',
                            'value' =>'0',
                           ),
                     );

?>