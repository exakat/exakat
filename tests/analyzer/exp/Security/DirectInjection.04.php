<?php

$expected     = array('f2($HTTP_RAW_POST_DATA, $http_raw_post_data)',
                      'f3($_SERVER[\'DOCUMENT_ROOT\'], $_SERVER[\'QUERY_STRING\'])',
                      'f4($_SERVER[\'DOCUMENT_ROOT\'], $_SERVER[\'PHP_SELF\'])',
                     );

$expected_not = array('$_SERVER[\'DOCUMENT_ROOT\']',
                      '$_SERVER[\'DOCUMENT_ROOT\']',
                      '$PHP_SELF',
                      '$php_self',
                      '$http_raw_post_data',
                      '$_SERVER[\'HTTP_HOST\']',
                      '$_SERVER[\'HTTP_PORT\']',
                     );

?>