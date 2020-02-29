<?php

$expected     = array(array('key' => 'LATIN1', 
                            'value' => 1,
                            ),
                      array('key' => 'utf-8', 
                            'value' => 2,
                            ),
                      array('key' => 'iso-8859-1', 
                            'value' => 1,
                            ),
                     );

$expected_not = array(array('key' => 'iso-8859-1', 
                            'value' => 2,
                            ),
                      array('key' => 'iso-8859-9', 
                            'value' => 0,
                            ),
                     );

?>