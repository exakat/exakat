<?php

$expected     = array('\'tmp/my/file\'',
                      '\'/tmp/my/file.txt\'',
                      '\'/www.other-example.com\'',
                      '\'tmp/my/file.txt\'',
                     );

$expected_not = array('\'https://www.other-example.com/\'',
                      '\'http://www.other-example.com/\'',
                     );

?>