<?php

$expected     = array('preg_replace(\'/[^\\w-.]/\', \'\', null)',
                     );

$expected_not = array('preg_match(\'/[\\xC0-\\xFF]/\',  null);',
                      'preg_match(\'/[ab\' . (C) . \']/\',  null)',
                     );

?>