<?php

$expected     = array('vsprintf(\'<a href="http://%1$s">%2$s</a>\', A3)',
                      'vsprintf(\'<a href="http://%1$s">%2$s</a>\', A1)',
                     );

$expected_not = array('vsprintf(\'<a href="http://%1$s">%2$s</a>\', A2)',
                      'vsprintf(\'<a href="http://%1$s">%2$s</a>\', array($a, $b))',
                     );

?>