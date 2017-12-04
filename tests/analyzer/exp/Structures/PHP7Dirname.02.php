<?php

$expected     = array('dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname($x))))))))',
                     );

$expected_not = array('dirname($new)',
                      'dirname($new->dirname($method))',
                      'dirname($method)',
                     );

?>