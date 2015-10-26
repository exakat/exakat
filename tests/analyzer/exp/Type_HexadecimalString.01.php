<?php

$expected     = array("' 0x123'",
                      "'0x123'",
                      '" 0x123"',
                      "'0xf23'",
                      "<<<'NOWDOC'
     0x123f34
NOWDOC",
                      "<<<HEREDOC
 0x123\$x
HEREDOC
",
                      "<<<HEREDOC
 0x123
HEREDOC
"
);

$expected_not = array();

?>