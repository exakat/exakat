<?php

$expected     = array('ftp_connect(A, 1)',
                      'ssh2_tunnel(2, EVARIABLE, 2)',
                      );

$expected_not = array('some_function(A, 2)',
                      'cubrid_pconnect(PHP_SELF)',
                      
                      );

?>