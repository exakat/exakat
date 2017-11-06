<?php

$expected     = array('strtotime("next month", mktime(0, 0, 0, 10, 31, 2017))',
                      'strtotime(\'+1 month\', mktime(0, 0, 0, 10, 31, 2017))',
                      'strtotime(\'+2 month\', mktime(0, 0, 0, 10, 31, 2017))',
                      'strtotime(\'-1 month\', mktime(0, 0, 0, 10, 31, 2017))',
                      'strtotime("+$x month", mktime(0, 0, 0, 10, 31, 2017))',
                     );

$expected_not = array('strtotime("first day of next month",mktime(0,0,0,10,31,2017)))',
                      'strtotime("last day of NEXT month",mktime(0,0,0,10,31,2017)))',
                     );

?>