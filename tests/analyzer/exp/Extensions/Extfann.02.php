<?php

$expected     = array('fann_create_from_file($train_file)',
                      'fann_destroy($ann)',
                      'fann_run($ann, $input)',
                     );

$expected_not = array('printf("xor test (%f,%f) -> %f\\n", $input[0], $input[1], $calc_out[0])',
                     );

?>