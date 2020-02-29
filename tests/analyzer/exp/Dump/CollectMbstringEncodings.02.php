<?php

$expected     = array(array('key' => 'iso-8859-9', 
                            'value' => 2,
                            ),
                      array('key' => 'utf-8', 
                            'value' => 1,
                            ),
                      array('key' => 'iso-8859-1', 
                            'value' => 1,
                            ),
                     );

$expected_not = array(array('key' => '$d', 
                            'value' => 1,
                            ),
                      array('key' => 'iso-8859-2', 
                            'value' => 1,
                            ),
                     );

?>