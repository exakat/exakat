<?php

$expected     = array('json_decode($straight)',
                      'json_decode($naked)',
                     );

$expected_not = array('json_decode($inTry)',
                      'json_decode($withCall)',
                      'json_decode($withCall2)',
                     );

?>