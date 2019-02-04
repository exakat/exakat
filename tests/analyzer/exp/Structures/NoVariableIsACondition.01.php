<?php

$expected     = array('!$m',
                      'elseif($c) { /**/ } ',
                      '$e ? 1 : 3',
                      'if($a) { /**/ } ',
                      'for($i = 10 ; $i ; --$i) { /**/ } ',
                     );

$expected_not = array('($p)',
                      'foo($q)',
                      '~$n',
                     );

?>