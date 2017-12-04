<?php

$expected     = array('filter_var($email_a, FILTER_VALIDATE_EMAIL)',
                      'filter_var($email_b, FILTER_VALIDATE_EMAIL)',
                      'FILTER_VALIDATE_EMAIL',
                      'FILTER_VALIDATE_EMAIL',
                     );

$expected_not = array(
                     );

?>