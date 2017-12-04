<?php

$expected     = array('echo mysql_error( )',
                      'die(imap_errors( ))',
                      'die(\'Error \' . pg_last_error( ) . "\\n")',
                     );

$expected_not = array('echo my_errors( )',
                      'die(\'Error \' . pg_error( ) . "\\n")',
                     );

?>