<?php

$expected     = array('switch ($bar) { /**/ } ',
                      'switch ($foo) { /**/ } ',
                     );

$expected_not = array('foreach ([\'foo2\'] as $thing) { /**/ } ',
                      'foreach ([\'foo\'] as $thing) { /**/ } ',
                     );

?>