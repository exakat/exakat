<?php

$expected     = array('switch ($three2) { /**/ } ',
                      'switch ($two_and_nested) { /**/ } ',
                      'switch ($three) { /**/ } ',
                      'switch ($two) { /**/ } ',
                     );

$expected_not = array('switch ($zero) { /**/ } ',
                      'switch ($one) { /**/ } ',
                      'switch ($three_but_nested) { /**/ } ',
                     );

?>