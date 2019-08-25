<?php

$expected     = array('sprintf(\'<a href="http://%s">%1$s</a>\', $a, $b, $c)',
                      'sprintf(\'<a href="http://%3$s">%1$s</a>\', $a, $b, $c)',
                      'sprintf(\'<a href="http://%1$s">%1$s</a>\', $a, $b)',
                     );

$expected_not = array('sprintf(\'<a href="http://%1$s">%1$s</a>\', $a, $b)',
                      '',
                     );

?>