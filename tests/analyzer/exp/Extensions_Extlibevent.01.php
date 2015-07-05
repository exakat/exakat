<?php

$expected     = array('event_new( )', 
                      'event_base_loopexit($arg[1])', 
                      'event_add($event)', 
                      'event_base_loop($base)', 
                      'event_base_set($event, $base)', 
                      'event_base_new( )', 
                      'event_set($event, $fd, EV_READ | EV_PERSIST, "print_line", array($event, $base))');

$expected_not = array();

?>