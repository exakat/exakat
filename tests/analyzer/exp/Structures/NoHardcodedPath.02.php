<?php

$expected     = array('opendir(\'C:\\\\htdocs\')',
                      'unlink(\'D:\\\\A\\B\\C\')',
                     );

$expected_not = array('\'php://stdout\'',
                      '\'php://fd34\'',
                     );

?>