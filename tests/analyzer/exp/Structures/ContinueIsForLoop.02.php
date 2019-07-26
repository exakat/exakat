<?php

$expected     = array('switch ($foo) { /**/ } ',
                     );

$expected_not = array('foreach([\'foo\'] as $thing) { /**/ } ',
                      'foreach([\'bar\'] as $thing) { /**/ } ',
                     );

?>