<?php

$expected     = array('foreach($arr as &$value_not_unset) { /**/ } ',
                      'foreach($arr as &$value_unset_other) { /**/ } ',
                     );

$expected_not = array('foreach ($arr as &$value_unset){ /**/ } ',
                      'foreach ($arr as $value_not_reference){ /**/ } ',
                     );

?>