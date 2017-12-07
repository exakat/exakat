<?php

$expected     = array('socket_create(AF_INET, SOCK_STREAM, SOL_TCP)',
                      'socket_strerror(socket_last_error( ))',
                      'socket_last_error( )',
                      'socket_bind($sock, $address, $port)',
                      'socket_strerror(socket_last_error($sock))',
                      'socket_last_error($sock)',
                      'socket_listen($sock, 5)',
                      'socket_strerror(socket_last_error($sock))',
                      'socket_last_error($sock)',
                      'SOCK_STREAM',
                      'SOL_TCP',
                      'AF_INET',
                     );

$expected_not = array(
                     );

?>