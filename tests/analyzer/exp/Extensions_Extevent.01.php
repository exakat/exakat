<?php

$expected     = array('EventBase( )', 
                      'EventDnsBase($base, TRUE)', 
                      'EventBufferEvent($base, NULL, EventBufferEvent::OPT_CLOSE_ON_FREE | EventBufferEvent::OPT_DEFER_CALLBACKS, "readcb", NULL, "eventcb")', 
                      'EventUtil', 
                      'Event', 
                      'Event', 
                      'EventBufferEvent', 
                      'EventBufferEvent', 
                      'EventBufferEvent', 
                      'EventBufferEvent', 
                      'EventBufferEvent', 
                      'EventBufferEvent'
);

$expected_not = array();

?>