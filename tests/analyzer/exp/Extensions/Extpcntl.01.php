<?php

$expected     = array('pcntl_signal(SIGUSR1, "sig_handler")',
                      'pcntl_signal(SIGHUP, "sig_handler")',
                      'pcntl_signal(SIGTERM, "sig_handler")',
                      'SIGTERM',
                      'SIGUSR1',
                      'SIGUSR1',
                      'SIGHUP',
                     );

$expected_not = array(
                     );

?>