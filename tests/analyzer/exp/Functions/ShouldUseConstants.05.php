<?php

$expected     = array('htmlspecialchars(\'a\', $v ?: 1 + 2)',
                     );

$expected_not = array('htmlspecialchars(\'a\', $v ?: 1 | 2)',
                      'htmlspecialchars(\'a\', $v ?: ENT_COMPAT)',
                      'htmlspecialchars(\'a\', $v ?: (ENT_COMPAT))',
                     );

?>