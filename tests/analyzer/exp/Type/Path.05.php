<?php

$expected     = array('\'/path/as2/concat.php\'',
                      '\'/path/as\' . \'/concat.inc\'',
                      '\'/concat.inc\'',
                      '<<<HERE
/path/as/heredoc.c
HERE',
                      '/path/as/heredoc.c',
                      '\'/a/b/c/\'',
                      '\'a\' . \'b\'',
                      '<<<URL
some URL
URL',
                     );

$expected_not = array('Not a path',
                     );

?>