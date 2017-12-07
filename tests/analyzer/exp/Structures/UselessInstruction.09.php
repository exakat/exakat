<?php

$expected     = array('array_replace($a)',
                     );

$expected_not = array('array_replace($a, $b)',
                      'array_replace(...$c)',
                      'array_merge($d)',
                      'array_merge_recursive($d)',
                     );

?>