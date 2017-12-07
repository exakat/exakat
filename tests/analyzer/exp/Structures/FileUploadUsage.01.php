<?php

$expected     = array('$_FILES',
                      '$_FILES',
                      '$_FILES',
                      'move_uploaded_file($_FILES[\'userfile\'][\'tmp_name\'], $uploadfile)',
                     );

$expected_not = array('move_uploaded_file($method)',
                      'move_uploaded_file($staticmethod)',
                     );

?>