<?php

$expected     = array('ftp_connect($ftp_server)',
                      'ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)',
                      'ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY)',
                      'ftp_close($conn_id)',
                      'FTP_BINARY',
                     );

$expected_not = array(
                     );

?>