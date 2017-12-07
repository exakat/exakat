<?php

$expected     = array('socket_create( )',
                     );

$expected_not = array('exec($a)',
                      'eval($b)',
                      'socket_connect( )',
                      'symlink( )',
                     );

?>