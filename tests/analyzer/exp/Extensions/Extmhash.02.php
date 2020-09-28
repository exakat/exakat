<?php

$expected     = array('mhash(\'hash\', \'data1\')',
                      '\\mhash(\'hash\', \'data4\')',
                     );

$expected_not = array('\\a\\mhash(\'hasha\', \'dataa2\')',
                      'a\\mhash(\'hasha\', \'dataa3\')',
                      'a\\mhash(\'hasha\', \'dataa3\')',
                     );

?>