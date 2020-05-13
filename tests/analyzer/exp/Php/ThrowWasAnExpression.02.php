<?php

$expected     = array('$condition || throw new Exception(\'$condition must be truthy\') && $condition2 || throw new Exception(\'$condition2 must be truthy\')',
                      'new Exception(\'$condition must be truthy\') && $condition2 || throw new Exception(\'$condition2 must be truthy\')',
                     );

$expected_not = array('throw new Exception(\'standalone\')',
                     );

?>