<?php

$expected     = array('pcntl_signal(SIGUSR1, "sig_handler")', 
                      'pcntl_signal(SIGHUP, "sig_handler")', 
                      'pcntl_signal(SIGTERM, "sig_handler")');

$expected_not = array();

?>