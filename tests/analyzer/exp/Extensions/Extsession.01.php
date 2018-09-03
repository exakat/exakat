<?php

$expected     = array('session_name(\'Private\')',
                      'session_start( )',
                      'session_id( )',
                      'session_name(\'Global\')',
                      'session_id(\'TEST\')',
                      'session_start( )',
                      'session_write_close( )',
                      'session_write_close( )',
                     );

$expected_not = array('session_just_close( )',
                     );

?>