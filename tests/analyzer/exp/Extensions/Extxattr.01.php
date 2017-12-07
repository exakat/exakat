<?php

$expected     = array('xattr_get($file, \'Listen count\')',
                      'xattr_set($file, \'My ranking\', \'Good\')',
                      'xattr_set($file, \'Listen count\', \'34\')',
                      'xattr_set($file, \'Artist\', \'Someone\')',
                     );

$expected_not = array('xattr_get($file, \'typo in functionname\')',
                     );

?>