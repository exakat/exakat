<?php

$expected     = array('foreach($row as $key => $value2) { /**/ } ',
                     );

$expected_not = array('foreach($row as $key12 => $value) { /**/ } ',
                      'foreach($row as $key2 => $value) { /**/ } ',
                      'foreach($row as $key1 => $value) { /**/ } ',
                     );

?>