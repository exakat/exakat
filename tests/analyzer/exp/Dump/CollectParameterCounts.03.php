<?php

$expected     = array(array('key' => 0,
                            'value'=> 1,
                            ),
                      array('key' => 1,
                            'value'=> 1,
                            ),
                      array('key' => 3,
                            'value'=> 4,
                            ),
                      array('key' => 4,
                            'value'=> 1,
                            ),
                     );

$expected_not = array(array('key' => 5,
                            'value'=> 1,
                            ),
                      array('key' => 2,
                            'value'=> 1,
                            ),
                      array('key' => 2,
                            'value'=> 0,
                            ),
                     );

?>