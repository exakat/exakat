<?php

$expected     = array('$q = urldecode($q)',
                      '$ini = parse_ini_string($ini)',
                      '$q = http_build_query($q)',
                     );

$expected_not = array('$q = urlencode($q)',
                      '$q = urldecode($Q)',
                     );

?>