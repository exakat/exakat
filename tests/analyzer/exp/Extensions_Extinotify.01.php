<?php

$expected     = array('inotify_read($fd)', 
                      'inotify_init( )', 
                      'inotify_read($fd)', 
                      'inotify_queue_len($fd)', 
                      'inotify_add_watch($fd, __FILE__, IN_ATTRIB)', 
                      'inotify_rm_watch($fd, $watch_descriptor)');

$expected_not = array();

?>