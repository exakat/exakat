<?php

$expected     = array( 'switch ($three) { /**/ } ',
                       'switch ($four) { /**/ } ',
                       'switch ($two) { /**/ } ',
);

$expected_not = array( 'switch ($zero) { /**/ } ',
                       'switch ($one) { /**/ } ',
                       'switch ($oneBis) { /**/ } ',
);

?>