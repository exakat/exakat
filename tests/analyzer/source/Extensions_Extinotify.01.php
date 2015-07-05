<?php
// Open an inotify instance
$fd = inotify_init();

// Watch __FILE__ for metadata changes (e.g. mtime)
$watch_descriptor = inotify_add_watch($fd, __FILE__, IN_ATTRIB);

// generate an event
touch(__FILE__);

// Read events
$events = inotify_read($fd);
print_r($events);

// The following methods allows to use inotify functions without blocking on inotify_read():

// - Using stream_select() on $fd:
$read = array($fd);
$write = null;
$except = null;
stream_select($read,$write,$except,0);

// - Using stream_set_blocking() on $fd
stream_set_blocking($fd, 0);
inotify_read($fd); // Does no block, and return false if no events are pending

// - Using inotify_queue_len() to check if event queue is not empty
$queue_len = inotify_queue_len($fd); // If > 0, inotify_read() will not block

// Stop watching __FILE__ for metadata changes
inotify_rm_watch($fd, $watch_descriptor);

// Close the inotify instance
// This may have closed all watches if this was not already done
fclose($fd);

?>