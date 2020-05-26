<?php

$expected     = array('array_multisort($order, SORT_NUMERIC, SORT_DESC, $c->results)',
                     );

$expected_not = array('array_change_key_case($part, CASE_UPPER)',
                      'array_multisort($order, SORT_NUMERIC, SORT_DESC, $c->results)',
                      'htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, $charset)',
                     );

?>