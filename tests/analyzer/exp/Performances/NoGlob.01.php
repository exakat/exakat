<?php

$expected     = array('scandir(\'docs/\')',
                      '\\glob(\'docs/*\')',
                      'glob(dirname($pattern) . \'/*\', GLOB_ONLYDIR | GLOB_NOESCAPE)',
                     );

$expected_not = array('scandir(\'docs/\', SCANDIR_SORT_NONE)',
                      'scandir(\'docs/\', SCANDIR_SORT_ASCENDING)',
                      'scandir(\'docs/\', SCANDIR_SORT_DESCENDING)',
                      'glob(dirname($pattern) . \'/*\', GLOB_ONLYDIR | GLOBL_NOSORT)',
                      'glob(\'docs/*\', GLOB_NOSORT)',
                     );

?>