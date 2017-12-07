<?php

$expected     = array('(new DateTime( ))->format(\'u\') === $now->format(\'u\')',
                      '$now == date_create( )',
                     );

$expected_not = array('$now === H::format(\'u\')',
                      '$now === $h->format(\'H\')',
                     );

?>