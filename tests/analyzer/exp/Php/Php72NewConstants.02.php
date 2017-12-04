<?php

$expected     = array('\'CURLSSLOPT_NO_REVOKE\'',
                      'b\'CURLMOPT_PUSHFUNCTION\'',
                     );

$expected_not = array('\'\\CURLOPT_DEFAULT_PROTOCOL\'',
                      '\'\\SQLITE3_DETERMINISTIC\'',
                      '\'CURLSSLOPT_NO_REVOKE\'',
                      '"CURLSSLOPT_NO_$b"',
                      'b\'CURLOPT_STREAM_NOT_CURL\'',
                      '\\\'\\\'',
                     );

?>