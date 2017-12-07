<?php

$expected     = array('customConstant',
                      'customConstantByDefine',
                      '\\customConstantUsedWithNsname',
                      '\\customConstantByDefineUsedWithNsname',
                     );

$expected_not = array('unusedCustomConstant',
                      'unusedCustomConstantByDefine',
                      'MYSQLI_TYPE_STRING',
                      '\\MYSQLI_REFRESH_SLAVE;',
                     );

?>