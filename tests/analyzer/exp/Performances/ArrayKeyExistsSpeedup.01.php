<?php

$expected     = array('array_key_exists($foo[123]) || isset($foo[123])', 
                      'isset($foo[123]) || array_key_exists($foo[123])',
                     );

$expected_not = array('array_key_exists($foo[123])',
                      'isset($foo[123])',
                     );

?>