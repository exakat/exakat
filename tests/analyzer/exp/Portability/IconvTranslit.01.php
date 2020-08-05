<?php

$expected     = array('iconv(\'utf-8\', \'utf-8//TRANSLit\', $string)', 
                      'iconv(\'utf-8\', \'ascii//translit\', $string)',
                     );

$expected_not = array('iconv(\'utf-8\', \'utf-8//translitt\', $string)',
                      'iconv(\'utf-8\', \'ascii/translit\', $string)',
                      'iconv(\'utf-8\', \'ascii/bouh\', $string)',
                     );

?>