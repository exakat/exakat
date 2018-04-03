<?php

$expected     = array('ssh2_connect(\'shell.example.com\', 22)',
                      'ssh2_auth_password($connection, \'username\', \'password\')',
                      'ssh2_exec($connection, \'/usr/local/bin/php -i\', SSH2_SECURE_CONSTANT)',
                     );

$expected_not = array('SSH2_SECURE_CONSTANT',
                     );

?>