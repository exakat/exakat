<?php

$expected     = array('EV_READ',
                      'EV_PERSIST',
                      'event_add($event)',
                      'event_new( )',
                      'event_set($event, $fd, EV_READ | EV_PERSIST, "print_line", array($event, $base))',
                      'event_base_loop($base)',
                      'event_base_loopexit($arg[1])',
                      'event_base_set($event, $base)',
                      'event_base_new( )',
                     );

$expected_not = array(
                     );

?>