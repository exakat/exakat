<?php

$expected     = array('mysql_connect($a, $b, $c)',
                     'mysql_connect($a3, $c3, $b3)',
                     );

$expected_not = array('mysql_connect($a2, $c2, $b2)',
                     );

?>