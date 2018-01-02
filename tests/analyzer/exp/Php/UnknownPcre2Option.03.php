<?php

$expected     = array('preg_replace(\'/(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)/S\', \'<a href="$1" target="_blank">$1</a>\', $result)',
                      'preg_replace(\'?(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)?S\', \'<a href="$1" target="_blank">$1</a>\', $result)',
                      'preg_match("
            /^bc/
            xiS", $url)',
                     );

$expected_not = array('preg_match("
            /^bc/
            xi", $url)',
                     );

?>