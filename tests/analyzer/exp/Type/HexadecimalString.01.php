<?php

$expected     = array( '\'0x123\'', 
 ' 0x128', 
 ' 0x2', 
 '     0x127f34
', 
 '\' 0x124\'', 
 '" 0x125"', 
 ' 0x224
',"'0xf23'",
                      ' 0x129', 
                      ' 0x126
',
                      "<<<'NOWDOC'
     0x127f34
NOWDOC",
                      "<<<HEREDOC
 0x128\$x
HEREDOC
",
                      "<<<HEREDOC
 0x126
HEREDOC
"
);

$expected_not = array();

?>