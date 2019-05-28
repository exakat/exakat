<?php

$expected     = array('pg_set_client_encoding( ) ? 1 : 3',
                      'if(openssl_verify(1, 2, 3, 4)) { /**/ } elseif(openssl_verify(1, 2, 3, 4)) { /**/ } elseif(openssl_x509_checkpurpose( ) == 1) { /**/ } ',
                      'elseif(openssl_verify(1, 2, 3, 4)) { /**/ } elseif(openssl_x509_checkpurpose( ) == 1) { /**/ } ',
                      'while (pcntl_wait( )) { /**/ } ',
                      'do { /**/ } while(pcntl_wait( ))',
                     );

$expected_not = array('elseif (openssl_x509_checkpurpose() == 1) { /**/ } ',
                     );

?>