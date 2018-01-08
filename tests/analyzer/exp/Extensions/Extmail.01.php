<?php

$expected     = array('mail(\'caffeinated@example.com\', \'My Subject\', $message)',
                     );

$expected_not = array('Mail(\'notCaffeinated@example.com\', \'My Other Subject\', $message2)',
                     );

?>