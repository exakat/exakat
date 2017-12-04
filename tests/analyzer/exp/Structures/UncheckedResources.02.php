<?php

$expected     = array('readdir(opendir(\'uncheckedDir4\'))',
                      '$uncheckedDir = opendir(\'.\')',
                      '$uncheckedDir3 = fopen(\'.\', \'r+\')',
                      '$pspell_new = pspell_new(\'asdfasdf\')',
                      '$uncheckedDir2 = bzopen(\'.\')',
                     );

$expected_not = array('readdir2(opendir(\'uncheckedDir5\'))',
                      'readdir(opendir2(\'uncheckedDir6\'))',
                     );

?>