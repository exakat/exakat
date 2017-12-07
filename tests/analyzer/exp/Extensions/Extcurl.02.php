<?php

$expected     = array('curl_exec($ch)',
                      'curl_close($ch)',
                      'curl_init("http://www.example.com/")',
                      'curl_setopt($ch, CURLOPT_FILE, $fp)',
                      'curl_setopt($ch, CURLOPT_HEADER, 0)',
                      'CURLOPT_FILE',
                      'CURLOPT_HEADER',
                     );

$expected_not = array('fclose',
                     );

?>