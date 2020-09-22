<?php

$expected     = array('\'tmp/my/file3\'',
                      '\'/tmp/my/file.txt\'',
                      '\'/www.other-example2.com\'',
                      '\'tmp/my/file2.txt\'',
                      '\'/www.other-example.com/\'',
                     );

$expected_not = array('\'https://www.other-example.com/\'',
                      '\'http://www.other-example.com/\'',
                     );

?>