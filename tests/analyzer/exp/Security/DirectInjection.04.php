<?php

$expected     = array('$HTTP_RAW_POST_DATA',
                      '$_SERVER[\'QUERY_STRING\']',
                      '$_SERVER[\'QUERY_STRING\']',
                      '$PHP_SELF',
                      );

$expected_not = array('$_SERVER[\'DOCUMENT_ROOT\']',
                      '$_SERVER[\'DOCUMENT_ROOT\']',
                      '$php_self',
                      '$http_raw_post_data',
                      '$_SERVER[\'HTTP_HOST\']', 
                      '$_SERVER[\'HTTP_PORT\']',
                      );

?>