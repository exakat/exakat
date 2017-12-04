<?php

$expected     = array('const f = "f" . "g"',
                      'const b = a ? 2 : 100',
                     );

$expected_not = array('const a = 1',
                      'const c = true',
                      'const d = 3.4',
                      'const e = "d"',
                      'const g = self::d',
                     );

?>