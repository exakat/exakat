<?php

$expected     = array('substr($a, strlen($f), strlen($a))',
                     );

$expected_not = array('substr($b, strlen($f))',
                      'substr($c, strlen($f), strlen($d))',
                     );

?>