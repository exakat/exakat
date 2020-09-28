<?php

$expected     = array('(double) imagerotate( )',
                      '(float) acosh( )',
                      '(array) array_filter($a, $b)',
                      '(int) memory_get_peak_usage( )',
                      '(integer) curl_errno( )',
                      '(bool) array_key_exists($a, $b)',
                      '(boolean) phpinfo( )',
                      '(string) trim($a)',
                     );

$expected_not = array('(integer) $a->curl_errno()',
                      '(integer) A::curl_errno()',
                      '(integer) $a->$curl_errno()',
                      '(integer) A::$curl_errno()',
                     );

?>