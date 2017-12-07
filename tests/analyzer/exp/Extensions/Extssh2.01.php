<?php

$expected     = array('ssh2_connect(\'shell.example.com\', 22)',
                      'ssh2_auth_password($connection, \'username\', \'password\')',
                      'ssh2_exec($connection, \'/usr/local/bin/php -i\')',
                     );

$expected_not = array(
                     );

?>