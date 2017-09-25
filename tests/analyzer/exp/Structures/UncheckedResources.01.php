<?php

$expected     = array('$uncheckedDir = opendir(\'.\')',
                      '$uncheckedDir3 = opendir(\'.\')',
                      '$uncheckedDir7 = opendir(\'asdfasdf\')',
                      '$uncheckedDir2 = opendir(\'.\')',
                      'readdir(opendir(\'uncheckedDir4\'))',
                     );

$expected_not = array('opendir(\'uncheckedDir5\')',
                      'opendir2(\'uncheckedDir6\'))',
                      '$checkedDir1 = opendir(\'.\')',
                      '$checkedDir2 = opendir(\'.\')',
                      '$checkedDir3 = opendir(\'.\')',
                      '$checkedDir4 = opendir(\'.\')',
                     );

?>