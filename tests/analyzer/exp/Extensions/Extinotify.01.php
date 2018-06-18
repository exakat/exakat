<?php

$expected     = array('inotify_read($fd)',
                      'inotify_init( )',
                      'inotify_queue_len($fd)',
                      'inotify_rm_watch($fd, $watch_descriptor)',
                      'inotify_add_watch($fd, __FILE__, IN_ATTRIB)',
                      'inotify_read($fd)',
                      'IN_ATTRIB',
                     );

$expected_not = array('fclose($fd)',
                     );

?>