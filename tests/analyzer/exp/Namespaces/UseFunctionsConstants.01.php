<?php

$expected     = array('use function foo\\bar as foo_bar',
                      'use const foo\\BAZ as FOO_BAZ0',
                      'use function foo\\bar2 as foo_bar2, foo\\bar3 as foo_bar3, foo\\bar4 as foo_bar4',
                      'use const foo\\BAZ1 as FOO_BAZ_CONST1, foo\\BAZ2 as FOO_BAZ_CONST2, foo\\BAZ3 as FOO_BAZ_CONST3',
                     );

$expected_not = array('use foo3\\BAZ as FOO_BAZ1_CLASS',
                      'use foo2\\BAZ as FOO_BAZ2_CLASS',
                     );

?>