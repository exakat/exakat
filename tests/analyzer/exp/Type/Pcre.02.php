<?php

$expected     = array('\'{__NORUNTIME__}\'',
                      '\'{_run_insert (.*)}\'',
                      '\'/* android */\'',
                      '\'~_run_in(.*)~\'',
                     );

$expected_not = array('\'###\'',
                      '\'{}\'',
                      '\'//\'',
                      '\\\'/android/iphone/i\\\'',
                     );

?>