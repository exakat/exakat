<?php

$expected     = array('curl_version(CURLVERSION_TOMORROW)',
                     );

$expected_not = array('curl_version(\\CURLVERSION_NOW)',
                      'curl_version(CURLVERSION_NOW)',
                     );

?>