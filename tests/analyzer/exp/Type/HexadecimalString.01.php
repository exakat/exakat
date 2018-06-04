<?php

$expected     = array('\'0x123\'',
                      '\' 0x124\'',
                      '" 0x125"',
                      '\'0xf23\'',
                      ' 0x129',
                      '<<<\'NOWDOC\'
     0x127f34
NOWDOC',
                      '<<<HEREDOC
 0x128$x
HEREDOC',
                      '<<<HEREDOC
 0x126
HEREDOC',
                      '" 0x2$n23"',
                      '" 0x129$x"',
                      '" 0x12G9$x"',
                      ' 0x2',
                      ' 0x12G9',
                     );

$expected_not = array(' 0x126
',
                      ' 0x128',
                     );

?>