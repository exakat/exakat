<?php

$expected     = array('mb_regex_encoding(\'CP936CP936\')',
                      'mb_convert_case($str, MB_CASE_UPPER, "UTF-89")',
                      '\\mb_strimwidth("Hello World", 0, 10, "...", \'UUENCODE2\')',
                      'mb_check_encoding($string, \'uft-9\')',
                      '\\mb_strimwidth("Hello World", 0, 10, "...", UUENCODE)',
                      '\\mb_strimwidth("Hello World", 0, 10, "...", ($x = UUENCODE))',
                     );

$expected_not = array('mb_regex_encoding(\'CP936\')',
                      'mb_check_encoding($string, \'uft-8\')',
                      '\\mb_strimwidth("Hello World", 0, 10, "...", HIDDEN)',
                     );

?>