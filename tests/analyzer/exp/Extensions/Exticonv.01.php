<?php

$expected     = array('iconv("UTF-8", "ISO-8859-1//TRANSLIT", $text)',
                      'iconv("UTF-8", "ISO-8859-1//IGNORE", $text)',
                      'iconv("UTF-8", "ISO-8859-1", $text)',
                     );

$expected_not = array('iconv("UTF-8")',
                     );

?>