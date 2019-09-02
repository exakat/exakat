<?php

$expected     = array('array_merge(...$b)',
                      'array_merge(...$e, ...$f)',
                     );

$expected_not = array('array_merge($a)',
                      'array_merge($d, ...$c)',
                      'array_merge(...$g)',
                      'array_merge(...[\'a\']',
                     );

?>