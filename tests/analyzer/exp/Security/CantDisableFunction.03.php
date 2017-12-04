<?php

$expected     = array('socket_create( )',
                      'symlink( )',
                     );

$expected_not = array('exec($a)',
                      'eval($b)',
                      'socket_connect( )',
                     );

?>