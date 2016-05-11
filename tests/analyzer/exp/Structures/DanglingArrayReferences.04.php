<?php

$expected     = array('foreach($arr as $k => &$value_not_unset) { /**/ } ', 
                      'foreach($arr as $k => &$value_unset_other) { /**/ } ');

$expected_not = array('foreach ($arr as $k => &$value_unset){ /**/ } ',
                      'foreach ($arr as $k => $value_not_reference){ /**/ } ',
                      );

?>