<?php

$expected     = array('echo $e->getMessage( )',
                      'print $e->getMessage( )',
                      'echo $e->getStackAsString( )',
                      'echo $e',
                     );

$expected_not = array('strtolower($e->getMessage( ))',
                      'print $e->getMassage( )',
                      'strtolower($e->getStackAsString(3))',
                      'strtoupper($e)',
                      'strtoupper($a)',
                     );

?>