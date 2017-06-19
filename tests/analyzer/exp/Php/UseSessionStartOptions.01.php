<?php

$expected     = array('ini_set(\'session.gc_maxlifetime\', 60 * 60)',
                      'session_start( )', 
                      'ini_set(\'session.save_path\', \'_sessions\')');

$expected_not = array('session_start([WRONG])');

?>