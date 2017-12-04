<?php

$expected     = array('curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false)',
                      'curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0)',
                      '[\\CURLOPT_SSL_VERIFYPEER => 0]',
                      'array(CURLOPT_SSL_VERIFYPEER => 0)',
                      'array(\\CURLOPT_SSL_VERIFYPEER => 0)',
                      '[CURLOPT_SSL_VERIFYPEER => 0]',
                     );

$expected_not = array('array(\\CURLOPT_URL => \'a\')',
                      'array(CURLOPT_URL => \'a\')',
                      'curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 1)',
                     );

?>