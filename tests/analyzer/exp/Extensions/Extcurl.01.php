<?php

$expected     = array('curl_init("http://www.example.com/")',
                      'curl_setopt($ch, CURLOPT_FILE, $fp)',
                      'curl_setopt($ch, CURLOPT_HEADER, 0)',
                      'curl_exec($ch)',
                      'curl_close($ch)',
                      'CURLOPT_FILE',
                      'CURLOPT_HEADER',
                     );

$expected_not = array('curl_die( )',
                     );

?>