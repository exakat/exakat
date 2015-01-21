<?php

$expected     = array( 'switch ($two) { /**/ } ');

$expected_not = array( 'switch ($one) { /**/ } ',
                       'switch ($oneNested) { /**/ } ');

?>