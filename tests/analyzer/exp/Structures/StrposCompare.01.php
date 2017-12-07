<?php

$expected     = array('strpos($a, $b)',
                      'STRpos($a, $b)',
                      'STRPOS($a, $b)',
                     );

$expected_not = array('strPOS',
                      'stRPOS',
                      'strPOS($a, $b)',
                      'stRPOS($a, $b)',
                     );

?>