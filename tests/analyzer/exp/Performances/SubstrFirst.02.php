<?php

$expected     = array('stristr(\'a\' . strtoupper($string), $offset, $size)',
                      'strstr(foo(strtoupper($string)), $offset, $size)',
                      'substr(mb_string_convert($string), $offset, $size)',
                     );

$expected_not = array('$a->substr($string, \'1\'.$offset, $size)',
                      'ucfirst(substr($string, $offset, $size))',
                     );

?>