<?php

$expected     = array('echo $e->getMessage( )',
                      'print $e->getMessage( )',
                      'echo $e->getTraceAsString( )',
                      'echo $e',
                     );

$expected_not = array('strtolower($e->getMessage( ))',
                      'print $e->getMassage( )',
                      'strtolower($e->getTraceAsString(3))',
                      'strtoupper($e)',
                      'strtoupper($a)',
                     );

?>