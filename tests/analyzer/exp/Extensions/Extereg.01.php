<?php

$expected     = array('ereg("abc", $string)',
                      'ereg("^abc", $string)',
                      'ereg("abc$", $string)',
                      'eregi("(ozilla.[23]|MSIE.3)", $_SERVER["HTTP_USER_AGENT"])',
                      'ereg("([[:alnum:]]+) ([[:alnum:]]+) ([[:alnum:]]+)", $string, $regs)',
                      'ereg_replace("^", "<br />", $string)',
                      'ereg_replace("$", "<br />", $string)',
                      'ereg_replace("\\n", "", $string)',
                     );

$expected_not = array('ereg_split("\\n", "", $string)',
                     );

?>