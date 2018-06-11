<?php

$expected     = array('protected const b = 2',
                      'private const c = 3',
                      'public const a = 1',
                      'public const a = 1',
                     );

$expected_not = array('const d = 4',
                      'const d = 4',
                     );

?>