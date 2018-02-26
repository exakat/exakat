<?php

$expected     = array('md5(microtime( ))',
                      'sha1(microtime( ))',
                      'uniqid( )',
                      'uniqid( )',
                      'uniqid( )',
                      'uniqid( )',
                     );

$expected_not = array('sha256(uniqid())',
                     );

?>