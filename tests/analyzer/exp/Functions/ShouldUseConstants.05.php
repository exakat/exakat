<?php

$expected     = array('htmlspecialchars(\'a\', $v ?: 1 + 2)',
                      'htmlspecialchars(\'a\', $v ?: 1)',
                      'htmlspecialchars(\'a\', $v ?: 1 | 2)',
                      'htmlspecialchars(\'a\', $v ?? 1)', 
                      'htmlspecialchars(\'a\', $v ?? (ENT_COMPAT))',
                     );

$expected_not = array('htmlspecialchars(\'a\', $v ?: 1 | 2)',
                      'htmlspecialchars(\'a\', $v ?: ENT_COMPAT)',
                      'htmlspecialchars(\'a\', $v ?: (ENT_COMPAT))',
                     );

?>