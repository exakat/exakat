<?php

$expected     = array('\'php://memory\'',
                      '\'php://temp\'',
                      '\'zlib://my/file.txt\'',
                      '\'expect://\'',
                      '\'RAR://\'',
                     );

$expected_not = array('\'r+\'',
                      '\'r-\'',
                      '\'a+\'',
                      '\'ogg:/www.other-example.com/\'',
                      '\'ahttp://www.other-wrong-example.com/\'',
                      '\'phar:://\'',
                     );

?>