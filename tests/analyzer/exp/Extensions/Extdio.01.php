<?php

$expected     = array('dio_open(\'/dev/ttyS0\', O_RDWR | O_NOCTTY | O_NONBLOCK)',
                      'dio_fcntl($fd, F_SETFL, O_SYNC)',
                      'dio_read($fd, 256)',
                      'dio_tcsetattr($fd, array(\'baud\' => 9600, \'bits\' => 8, \'stop\' => 1, \'parity\' => 0))',
                      'O_NOCTTY',
                      'O_NONBLOCK',
                      'F_SETFL',
                      'O_SYNC',
                      'O_RDWR',
                     );

$expected_not = array('echo',
                     );

?>