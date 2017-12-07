<?php

$expected     = array('substr(\'a\' . strtoupper($string), $offset, $size)',
                      'mb_substr(foo(strtoupper($string)), $offset, $size)',
                      'substr(strtoupper($string), $offset, $size)',
                     );

$expected_not = array('substr(strtoupper($string), \'1\'.$offset, $size)',
                      'substr($string, \'1\' . $offset, $size)',
                     );

?>