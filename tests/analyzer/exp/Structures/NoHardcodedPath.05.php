<?php

$expected     = array('fopen(\'jackalope://asd.txt\', \'rwb+\')',
                      'fopen(\'file://tmp/temp.file.txt\', \'rwb+\')',
                      'fopen(\'ogg://some/file.ogg\', \'rwb+\')',
                      'fopen(\'/http/host3.com\', \'rwb+\')',
                      'fopen(\'http/host2.com\', \'rwb+\')',
                      'fopen("phar://some/archive.phar", \'rwb+\')',
                      'fopen("ssh2/$host2.com", \'rwb+\')',
                      'fopen("/ssh2/$host4.com", \'rwb+\')',
                      'fopen("jackalope://$asd.txt", \'rwb+\')',
                     );

$expected_not = array('fopen(\'jackalope://\' . $token . \'@\' . $this->session->getRegistryKey( ) . \':\' . $i . $this->path, \'rwb+\')',
                      'fopen(\'file://\' . $token . \'@\' . $this->session->getRegistryKey( ) . \':\' . $i . $this->path, \'rwb+\')',
                      'ssh2://host.com',
                     );

?>